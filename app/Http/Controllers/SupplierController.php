<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;


class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'opening_due' => 'nullable|numeric',
        ]);

        // অটো সাপ্লায়ার কোড জেনারেট (NSM-SUP-1001)
        $latestSupplier = Supplier::latest('id')->first();
        $nextId = $latestSupplier ? $latestSupplier->id + 1 : 1;
        $code = 'NSM-SUP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        Supplier::create([
            'code' => $code,
            'name' => $request->name,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'opening_due' => $request->opening_due ?? 0,
            'current_due' => $request->opening_due ?? 0,
            'status' => 'ACTIVE',
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier successfully registered.');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'status' => 'required|string',
        ]);

        $supplier->update([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier successfully updated.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    public function ledger(Supplier $supplier)
{
    // সাপ্লায়ারের সমস্ত কেনাকাটা (Purchases)
    $purchases = $supplier->purchases()->latest('purchase_date')->get();

    // সাপ্লায়ারকে দেওয়া সমস্ত পেমেন্ট (Supplier Payments)
    $payments = $supplier->payments()->latest('payment_date')->get();

    // সামারি হিসাব
    $totalPurchases = $purchases->sum('total_amount');
    $totalPaid = $payments->sum('amount');
    $currentDue = $supplier->current_due; // অথবা হিসাব করে বের করতে পারেন

    return view('suppliers.ledger', compact('supplier', 'purchases', 'payments', 'totalPurchases', 'totalPaid', 'currentDue'));
}
}
