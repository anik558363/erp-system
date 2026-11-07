<?php

namespace App\Http\Controllers;

use App\Models\PosSale;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosReturnController extends Controller
{
    // রিটার্ন লিস্ট পেজ
    public function index()
    {
        $returns = DB::table('pos_returns')
            ->join('pos_sales', 'pos_returns.sale_id', '=', 'pos_sales.id')
            ->join('products', 'pos_returns.product_id', '=', 'products.id')
            ->join('users', 'pos_returns.recorded_by', '=', 'users.id')
            ->select('pos_returns.*', 'pos_sales.sale_no', 'products.name as product_name', 'users.name as cashier_name')
            ->latest('pos_returns.created_at')
            ->paginate(15);

        return view('pos.returns.index', compact('returns'));
    }

    // নতুন রিটার্ন ফর্ম পেজ (ইনভয়েস নম্বর দিয়ে খোঁজা)
    public function create(Request $request)
    {
        $sale = null;
        if ($request->has('sale_no')) {
            $sale = PosSale::with(['items.product', 'customer'])->where('sale_no', $request->sale_no)->first();
        }
        return view('pos.returns.create', compact('sale'));
    }

    // রিটার্ন প্রসেস ও স্টক রিস্টোর করা (Store Return)
    public function store(Request $request)
    {
        $request->validate([
            'pos_sale_id' => 'required|exists:pos_sales,id',
            'return_items' => 'required|array',
            'return_items.*.product_id' => 'required|exists:products,id',
            'return_items.*.sale_item_id' => 'required|exists:pos_sale_items,id',
            'return_items.*.qty' => 'required|integer|min:1',
            'return_items.*.return_type' => 'required|in:GOOD,DAMAGED',
            'refund_method' => 'nullable|string',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $sale = PosSale::findOrFail($request->pos_sale_id);

            // বর্তমান ইউজারের ওপেন শিফট আইডি বের করা (যদি থাকে)
            $activeShift = DB::table('pos_shifts')
                ->where('cashier_id', auth()->id())
                ->where('status', 'OPEN')
                ->first();

            foreach ($request->return_items as $itemData) {
                if (!isset($itemData['selected']) || $itemData['selected'] != 1) {
                    continue; // সিলেক্ট না করা আইটেম স্কিপ করবে
                }

                $saleItem = $sale->items()->where('id', $itemData['sale_item_id'])->first();
                if (!$saleItem) continue;

                $qtyToReturn = (int) $itemData['qty'];
                if ($qtyToReturn > $saleItem->quantity) {
                    throw new \Exception('Return quantity cannot exceed original sold quantity!');
                }

                $refundAmount = $qtyToReturn * $saleItem->unit_price;

                // আপনার pos_returns টেবিলে ডাটা ইনসার্ট করা
                DB::table('pos_returns')->insert([
                    'sale_id' => $sale->id,
                    'sale_item_id' => $saleItem->id,
                    'product_id' => $itemData['product_id'],
                    'shift_id' => $activeShift ? $activeShift->id : null,
                    'return_type' => $itemData['return_type'], // GOOD বা DAMAGED
                    'quantity' => $qtyToReturn,
                    'refund_amount' => $refundAmount,
                    'refund_method' => $request->refund_method ?? 'CASH',
                    'reason' => $request->reason,
                    'recorded_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // যদি রিটার্ন টাইপ GOOD হয়, তবেই কেবল স্টক বা গোডাউনে পুনরায় যোগ হবে
                if ($itemData['return_type'] === 'GOOD') {
                    $stock = Stock::where('product_id', $itemData['product_id'])->first();
                    if ($stock) {
                        $stock->increment('physical_qty', $qtyToReturn);
                        $stock->increment('current_stock', $qtyToReturn);
                    }
                }
            }

            DB::commit();
            return redirect()->route('pos.returns.index')->with('success', 'POS Return processed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
