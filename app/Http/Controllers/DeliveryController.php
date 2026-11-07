<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeliveryController extends Controller
{
    // ডেলিভারি ড্যাশবোর্ড ও ফিল্ড কালেকশন পেজ
    public function index()
    {
        $user = auth()->user();

        // ডেলিভারি ম্যান বা রাইডার রোল যাদের আছে তাদের ফেচ করা (অফিস বা ওনারের জন্য)
        $deliveryMen = User::role('Delivery')->get();

        // কোয়েরি বিল্ডার শুরু
        $query = Order::with(['customer', 'deliveryMan', 'area']);

        // যদি ইউজার শুধু 'Delivery' রোল হয়, তবে শুধু তার নিজের অ্যাসাইন করা অর্ডার দেখাবে
        if ($user->hasRole('Delivery') && !$user->hasRole('Owner|Office')) {
            $query->where('delivery_man_id', $user->id);
        }

        $orders = $query->latest()->paginate(15);
        $totalOrders = $orders->total();

        // ক্যাশ ট্র্যাকিং ও সামারি হিসাব
        $cashQuery = Order::where('status', 'DELIVERED');
        if ($user->hasRole('Delivery') && !$user->hasRole('Owner|Office')) {
            $cashQuery->where('delivery_man_id', $user->id);
        }

        $totalCollectedCash = (clone $cashQuery)->sum('paid_amount');
        $totalToSubmit = (clone $cashQuery)->whereNull('reconciled_at')->sum('paid_amount');

        $customers = Customer::where('status', 'ACTIVE')->get();

        return view('delivery.index', compact('deliveryMen', 'orders', 'totalCollectedCash', 'totalToSubmit', 'totalOrders', 'customers'));
    }

    // অর্ডারে ডেলিভারি ম্যান অ্যাসাইন করা
    public function assignOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_man_id' => 'required|exists:users,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->delivery_man_id = $request->delivery_man_id;
        $order->status = 'DISPATCHED';
        $order->dispatched_at = now();
        $order->save();

        return redirect()->back()->with('success', 'Order successfully assigned to delivery man.');
    }

   // ডেলিভারি স্ট্যাটাস আপডেট (Delivered / Returned)
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string', // DELIVERED, RETURNED, etc.
        ]);

        $order->status = $request->status;

        if ($request->status == 'DELIVERED') {
            $order->delivered_at = now();
            // এখানে আর জোর করে paid_amount = total_amount করা হলো না,
            // ডেলিভারি ম্যান ফিল্ডে ক্যাশ কালেকশন করলে সেটি পেমেন্ট মডিউল বা অর্ডারের paid_amount এ হ্যান্ডেল হবে।
        }

        $order->save();

        return redirect()->back()->with('success', 'Delivery status updated successfully.');
    }

   // অফিস ক্যাশ রিকনসাইল বা জমা নেওয়া (Reconciliation)
    public function reconcileCash(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        DB::beginTransaction();
        try {
            // সিলেক্ট করা অর্ডারগুলোতে রিকনসাইল ডেটা আপডেট করা
            Order::whereIn('id', $request->order_ids)->update([
                'reconciled_by' => auth()->id(),
                'reconciled_at' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Cash successfully reconciled and submitted to office!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

  // ফিল্ড থেকে বকেয়া কালেকশন বা পেমেন্ট সেভ করা
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $paymentNo = 'PAY-' . date('ymd') . '-' . strtoupper(Str::random(4));

            // ১. পেমেন্ট রেকর্ড তৈরি করা
            $payment = CustomerPayment::create([
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

            // ২. সবচেয়ে গুরুত্বপূর্ণ অংশ: যদি পেমেন্টের সাথে order_id থাকে, তবে ওই অর্ডারের paid_amount আপডেট করা
            if (!empty($request->order_id)) {
                $order = Order::find($request->order_id);
                if ($order) {
                    $order->paid_amount = (float)$order->paid_amount + (float)$request->amount;
                    $order->save();
                }
            }

            // ৩. কাস্টমারের বর্তমান ডিউ (current_due) নিখুঁতভাবে রিক্যালকুলেট করে আপডেট করা
            $customer = Customer::findOrFail($request->customer_id);

            $openingDue = (float) ($customer->opening_due ?? 0);
            $totalOrdersAmount = (float) $customer->orders()->sum('total_amount');
            $totalOrderPaid = (float) $customer->orders()->sum('paid_amount');
            $totalExtraPayments = (float) $customer->payments()->sum('amount');

            $totalPaidAll = $totalOrderPaid + $totalExtraPayments;
            $customer->current_due = max(0, ($openingDue + $totalOrdersAmount) - $totalPaidAll);
            $customer->save();

            DB::commit();
            return redirect()->back()->with('success', 'Payment collected, order paid amount updated, and customer ledger synchronized!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

}
