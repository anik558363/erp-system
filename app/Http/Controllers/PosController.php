<?php

namespace App\Http\Controllers;

use App\Models\PosRegister;
use App\Models\PosShift;
use App\Models\PosSale;
use App\Models\PosSaleItem;
use App\Models\PosPayment;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    // POS টার্মিনাল পেজ
    public function index()
    {
        $products = Product::with('stock')->where('status', 'ACTIVE')->get();
        $customers = Customer::where('status', 'ACTIVE')->get();

        // ডিফল্ট রেজিস্টার বা একটি্টিভ শিফট চেক করা যেতে পারে
        return view('pos.index', compact('products', 'customers'));
    }

    // POS সেলস স্টোর বা কমপ্লিট করা
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'products' => 'required|array|min:1',
            'quantities' => 'required|array',
            'prices' => 'required|array',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $productIds = $request->products;

            // ১. স্টক চেক ও সাবটোটাল হিসাব
            for ($i = 0; $i < count($productIds); $i++) {
                $productId = $productIds[$i];
                $qty = $request->quantities[$i];
                $price = $request->prices[$i];

                $stock = Stock::where('product_id', $productId)->first();
                if (!$stock || $stock->current_stock < $qty) {
                    $prodName = Product::find($productId)->name ?? 'Item';
                    throw new \Exception("Insufficient stock for POS product: '{$prodName}'");
                }

                $subtotal += $qty * $price;
            }

            $saleNo = 'POS-' . date('ymd') . '-' . strtoupper(Str::random(4));

            // সাময়িক শিফট এবং রেজিস্টার আইডি (ডামি বা ডিফল্ট ১ ধরে নেওয়া হলো)
            $registerId = 1;
            $shiftId = 1;

            $totalAmount = $subtotal;
            $paidAmount = (float) $request->paid_amount;
            $changeAmount = max(0, $paidAmount - $totalAmount);
            $dueAmount = max(0, $totalAmount - $paidAmount);

            // ২. POS Sale রেকর্ড তৈরি
            $sale = PosSale::create([
                'sale_no' => $saleNo,
                'register_id' => $registerId,
                'shift_id' => $shiftId,
                'cashier_id' => auth()->id(),
                'customer_id' => $request->customer_id ?? null,
                'sale_date' => now(),
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'due_amount' => $dueAmount,
                'status' => 'COMPLETED',
                'note' => $request->note,
            ]);

            // ৩. Sale Items এবং স্টক কমানো
            for ($i = 0; $i < count($productIds); $i++) {
                $productId = $productIds[$i];
                $qty = $request->quantities[$i];
                $price = $request->prices[$i];
                $lineTotal = $qty * $price;

                $product = Product::find($productId);

                PosSaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'cost_price' => $product->purchase_price ?? 0,
                    'line_total' => $lineTotal,
                ]);

                // স্টক আপডেট
                $stock = Stock::where('product_id', $productId)->first();
                if ($stock) {
                    $stock->decrement('physical_qty', $qty);
                    $stock->decrement('current_stock', $qty);
                }
            }

            // ৪. POS Payment রেকর্ড
            PosPayment::create([
                'sale_id' => $sale->id,
                'method' => $request->payment_method,
                'amount' => $paidAmount,
                'paid_at' => now(),
                'received_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('pos.index')->with('success', 'POS Sale completed successfully! Sale No: ' . $saleNo);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


    // POS বিক্রির হিস্ট্রি ও লিস্ট (Sales History & Invoices)
    public function salesHistory(Request $request)
    {
        $sales = PosSale::with(['customer', 'cashier', 'register'])->latest()->paginate(15);
        return view('pos.sales.index', compact('sales'));
    }

    // নির্দিষ্ট POS বিক্রির রসিদ বা ইনভয়েস ডিটেইলস দেখা (Invoice View)
    public function showReceipt(PosSale $sale)
    {
        $sale->load(['items.product', 'customer', 'cashier', 'payments', 'register']);
        return view('pos.sales.receipt', compact('sale'));
    }

    // POS সেলস বাতিল বা ভয়েড করা (Void Sale & Stock Restore)
    public function destroySale(PosSale $sale)
    {
        DB::beginTransaction();
        try {
            // সেল বাতিল হলে স্টক পুনরায় ফিরিয়ে দেওয়া
            foreach ($sale->items as $item) {
                $stock = \App\Models\Stock::where('product_id', $item->product_id)->first();
                if ($stock) {
                    $stock->increment('physical_qty', $item->quantity);
                    $stock->increment('current_stock', $item->quantity);
                }
            }

            $sale->items()->delete();
            $sale->payments()->delete();
            $sale->delete();

            DB::commit();
            return redirect()->route('pos.sales.index')->with('success', 'POS Sale voided and stock restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
