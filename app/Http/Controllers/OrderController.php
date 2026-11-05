<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\User;
use App\Models\Area;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
     $query =  Order::with(['customer', 'salesman', 'area', 'items.product']);

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(10)->appends($request->query());

        $customers = Customer::where('status', 'ACTIVE')->get();
        $products = Product::where('status', 'ACTIVE')->get();

        return view('orders.index', compact('orders', 'customers', 'products'));
    }

    // নতুন অর্ডার তৈরির ফর্ম
    public function create()
    {
        $customers = Customer::where('status', 'ACTIVE')->get();
        $salesmen = User::all();
        $areas = Area::all();
        $products = Product::with('stock')->where('status', 'ACTIVE')->get();

        return view('orders.create', compact('customers', 'salesmen', 'areas', 'products'));
    }

    // অর্ডার স্টোর বা সেভ করা লজিক
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'salesman_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'products' => 'required|array|min:1',
            'quantities' => 'required|array',
            'prices' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $productIds = $request->products;

            // ১. প্রথম step: অর্ডার করার আগে স্টক পর্যাপ্ত আছে কিনা তা চেক করা (Validation)
            for ($i = 0; $i < count($productIds); $i++) {
                $productId = $productIds[$i];
                $qty = $request->quantities[$i];
                $price = $request->prices[$i];

                $stock = Stock::where('product_id', $productId)->first();

                // যদি স্টক না থাকে বা অর্ডার করা কোয়ান্টিটি বর্তমান স্টকের চেয়ে বেশি হয়
                if (!$stock || $stock->physical_qty < $qty) {
                    $prodName = Product::find($productId)->name ?? 'Item';
                    throw new \Exception("Insufficient stock for product: '{$prodName}'. Available stock: " . ($stock->physical_qty ?? 0) . " units.");
                }

                $totalAmount += $qty * $price;
            }

            // কাস্টমার খুঁজে বের করা যাতে তার area_id পাওয়া যায়
            $customer = Customer::find($request->customer_id);

            // ইউনিক অর্ডার নম্বর জেনারেট করা
            $orderNo = 'NSM-ORD-' . date('ymd') . '-' . strtoupper(Str::random(4));

            // ২. অর্ডার তৈরি করা
            $order = Order::create([
                'order_no' => $orderNo,
                'customer_id' => $request->customer_id,
                'salesman_id' => $request->salesman_id,
                'order_date' => $request->order_date,
                'area_id' => $customer->area_id ?? null,
                'total_amount' => $totalAmount,
                'net_amount' => $totalAmount,
                'paid_amount' => $request->paid_amount ?? 0,
                'status' => 'PENDING_APPROVAL',
                'note' => $request->note,
            ]);

            // ৩. অর্ডার আইটেম সেভ করা এবং স্টক থেকে কোয়ান্টিটি মাইনাস করা
            for ($i = 0; $i < count($productIds); $i++) {
                $productId = $productIds[$i];
                $qty = $request->quantities[$i];
                $price = $request->prices[$i];

                $lineTotal = $qty * $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'unit_rate' => $price,
                    'line_total' => $lineTotal,
                ]);

                // স্টক টেবিল আপডেট (physical_qty এবং current_stock কমানো)
                $stock = Stock::where('product_id', $productId)->first();
                if ($stock) {
                    $stock->decrement('physical_qty', $qty);
                    $stock->decrement('current_stock', $qty);
                }
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order created successfully and stock updated!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    // মডাল থেকে অর্ডার স্ট্যাটাস এবং স্টক ম্যানেজমেন্ট আপডেট করার লজিক
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:PENDING_APPROVAL,APPROVED,PACKED,DISPATCHED,DELIVERED,REJECTED',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // যদি অর্ডারটি আগে APPROVED না থাকে এবং নতুন স্ট্যাটাস APPROVED করা হয়, তবে স্টক কাটবে (যদি আগে স্টক না কাটা হয়ে থাকে)
            // Note: যদি স্টোর করার সময়ই স্টক কেটে ফেলেন, তবে এখানে আবার কাটার দরকার নেই।
            // আর যদি স্টোর করার সময় না কেটে শুধু অ্যাপ্রুভ করার সময় কাটতে চান, তবে নিচে স্টক কাটার লজিক রাখতে পারেন।

            $order->update([
                'status' => $newStatus
            ]);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order status updated successfully and workflow synchronized!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // অর্ডার বিস্তারিত দেখা
    public function show(Order $order)
    {
        $order->load(['customer', 'salesman', 'area', 'deliveryMan', 'items.product']);
        return view('orders.show', compact('order'));
    }
}
