<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerPaymentController extends Controller
{

    public function index(Request $request)
    {
        $query = CustomerPayment::with(['customer', 'collectedBy', 'order']);

        if ($request->has('customer_id') && !empty($request->customer_id)) {
            $query->where('customer_id', $request->customer_id);
        }

        $payments = $query->latest()->paginate(15)->appends($request->query());
        $customers = Customer::where('status', 'ACTIVE')->get();

        return view('customers.payments.index', compact('payments', 'customers'));
    }


    // ফিল্ড থেকে বকেয়া কালেকশন বা পেমেন্ট সেভ করা
    public function store(Request $request)
    {



        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $paymentNo = 'PAY-' . date('ymd') . '-' . strtoupper(Str::random(4));

            // ১. পেমেন্ট রেকর্ড তৈরি করা
            CustomerPayment::create([
                'payment_no' => $paymentNo,
                'customer_id' => $request->customer_id,
                'order_id' => $request->order_id ?? null,
                'collected_by' => auth()->id(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_no' => $request->reference_no,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
            ]);

            // ২. কাস্টমারের বর্তমান ডিউ (current_due) নিখুঁতভাবে রিক্যালকুলেট করে আপডেট করা
            $customer = Customer::findOrFail($request->customer_id);

            $openingDue = (float) ($customer->opening_due ?? 0);
            $totalOrdersAmount = (float) $customer->orders()->sum('total_amount');
            $totalOrderPaid = (float) $customer->orders()->sum('paid_amount');
            $totalExtraPayments = (float) $customer->payments()->sum('amount');

            // সঠিক সূত্র: (ওপেনিং ডিউ + মোট অর্ডার) - (অর্ডারের সাথে দেওয়া পেইড + আলাদা পেমেন্টগুলো)
            $totalPaidAll = $totalOrderPaid + $totalExtraPayments;
            $customer->current_due = max(0, ($openingDue + $totalOrdersAmount) - $totalPaidAll);
            $customer->save();

            DB::commit();
            return redirect()->back()->with('success', 'Payment collected successfully and customer ledger updated!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


}
