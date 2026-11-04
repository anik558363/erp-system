<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{

public function index(Request $request)
{
    $query = Purchase::with(['supplier', 'items.product']);

    // Status Filter (RECEIVED বা DRAFT ফিল্টার করার জন্য)
    if ($request->has('status') && !empty($request->status)) {
        $query->where('status', $request->status);
    }

    // Search Filter (Supplier company name বা Invoice No দিয়ে সার্চ করার জন্য)
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('chalan_no', 'like', "%{$search}%")
              ->orWhereHas('supplier', function($sq) use ($search) {
                  $sq->where('company_name', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
              });
        });
    }

    $purchases = $query->latest()->paginate(10)->appends($request->query());
    $suppliers = Supplier::where('status', 'ACTIVE')->get();
    $products = Product::with('stock')->where('status', 'ACTIVE')->get();

    return view('purchases.index', compact('purchases', 'suppliers', 'products'));
}

    public function create()
    {
        $suppliers = Supplier::where('status', 'ACTIVE')->get();
        $products = Product::where('status', 'ACTIVE')->get();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {



        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'chalan_no' => 'required|string|unique:purchases,chalan_no',
            'purchase_date' => 'required|date',
            'products' => 'required|array|min:1',
            'quantities' => 'required|array',
            'prices' => 'required|array',
            'paid_amount' => 'required|numeric',
            'note' => 'nullable|string',
            'status' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $productIds = $request->products;

            // Calculate total amount first
            for ($i = 0; $i < count($productIds); $i++) {
                $totalAmount += $request->quantities[$i] * $request->prices[$i];
            }

            $paidAmount = $request->paid_amount;
            $dueAmount = $totalAmount - $paidAmount;


           // Create Purchase Invoice
$purchase = Purchase::create([
    'chalan_no' => $request->chalan_no,
    'supplier_id' => $request->supplier_id,
    'purchase_date' => $request->purchase_date,
    'total_amount' => $totalAmount,
    'paid_amount' => $paidAmount,
    'due_amount' => $dueAmount,
    'note' => $request->note,
    'status' => $request->status ?? 'RECEIVED', // User jeta select korbe ba default RECEIVED
]);

            // Save Purchase Items and Update Stock
            for ($i = 0; $i < count($productIds); $i++) {
                $productId = $productIds[$i];
                $qty = $request->quantities[$i];
                $price = $request->prices[$i];
                $subTotal = $qty * $price;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'purchase_price' => $price,
                    'sub_total' => $subTotal,
                ]);

                // Update Stock (physical_qty increase)
                $stock = Stock::where('product_id', $productId)->first();
                if ($stock) {
                    $stock->increment('physical_qty', $qty);
                } else {
                    Stock::create([
                        'product_id' => $productId,
                        'physical_qty' => $qty,
                        'current_stock' => $qty
                    ]);
                }
            }

            // Update Supplier Current Due
            $supplier = Supplier::find($request->supplier_id);
            if ($supplier) {
                $supplier->increment('current_due', $dueAmount);
            }

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase recorded and stock successfully updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }
}
