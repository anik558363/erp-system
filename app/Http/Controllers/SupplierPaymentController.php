<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SupplierPaymentController extends Controller
{
    // পেমেন্টের হিস্ট্রি বা পেজ দেখানোর জন্য
    public function index()
    {
        $payments = SupplierPayment::with(['supplier', 'purchase'])->latest()->paginate(10);
        $suppliers = Supplier::where('status', 'ACTIVE')->get();

        return view('suppliers.payments.index', compact('payments', 'suppliers'));
    }


    public function store(Request $request)
{
    // ১. প্রথমে সাপ্লায়ারের বর্তমান ডিউ বা ব্যালেন্স বের করা
    $supplier = Supplier::findOrFail($request->supplier_id);

    // সাপ্লায়ারের মোট ডিউ হিসাব করা (যদি current_due কলাম না থাকে তবে পার্চেস থেকে সাম করা যেতে পারে)
    $maxDue = $supplier->current_due ?? 0;

    // যদি নির্দিষ্ট কোনো চালানের (purchase_id) বিপরীতে পেমেন্ট দেয়, তবে ঐ চালানের ডিউ চেক করব
    if ($request->filled('purchase_id')) {
        $purchase = Purchase::find($request->purchase_id);
        if ($purchase) {
            $maxDue = $purchase->due_amount;
        }
    }

    // ২. ভ্যালিডেশন রুল (amount যেন কোনোভাবেই ডিউ টাকার চেয়ে বেশি না হয়)
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'amount' => 'required|numeric|min:0.01|max:' . $maxDue, // <--- max এ current due বসিয়ে দেওয়া হলো
        'payment_date' => 'required|date',
        'payment_method' => 'required|string',
    ], [
        'amount.max' => 'Payment amount cannot exceed the supplier current due amount (৳' . number_format($maxDue, 2) . ')',
    ]);

    try {
        DB::beginTransaction();

        // পেমেন্ট রেকর্ড সংরক্ষণ
        SupplierPayment::create([
            'supplier_id' => $request->supplier_id,
            'purchase_id' => $request->purchase_id ?? null,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'reference_no' => $request->reference_no,
            'note' => $request->note,
            'payment_date' => $request->payment_date,
        ]);

        $paymentAmount = $request->amount;

        // নির্দিষ্ট চালানের বিপরীতে হলে আপডেট
        if ($request->filled('purchase_id')) {
            $purchase = Purchase::findOrFail($request->purchase_id);
            $purchase->paid_amount += $paymentAmount;
            $purchase->due_amount = max(0, $purchase->total_amount - $purchase->paid_amount);
            $purchase->save();
        }
        else {
            // জেনারেল পেমেন্ট হলে পুরোনো ডিউ থেকে অ্যাডজাস্ট হবে
            $unpaidPurchases = Purchase::where('supplier_id', $request->supplier_id)
                ->where('due_amount', '>', 0)
                ->oldest('purchase_date')
                ->get();

            foreach ($unpaidPurchases as $purchase) {
                if ($paymentAmount <= 0) break;

                if ($paymentAmount >= $purchase->due_amount) {
                    $paymentAmount -= $purchase->due_amount;
                    $purchase->paid_amount += $purchase->due_amount;
                    $purchase->due_amount = 0;
                } else {
                    $purchase->paid_amount += $paymentAmount;
                    $purchase->due_amount -= $paymentAmount;
                    $paymentAmount = 0;
                }
                $purchase->save();
            }
        }

        // Supplier টেবিলের ডিউ ব্যালেন্স আপডেট করা
        if (Schema::hasColumn('suppliers', 'current_due')) {
            $supplier->current_due = max(0, $supplier->current_due - $request->amount);
            $supplier->save();
        }

        DB::commit();

        return redirect()->back()->with('success', 'Supplier payment recorded and all due balances updated successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

}
