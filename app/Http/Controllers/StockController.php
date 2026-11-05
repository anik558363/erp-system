<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with('product.category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $stocks = $query->orderBy('id', 'desc')->paginate(10);

        // Summation fɔ di samari kads
        $totalPhysical = Stock::sum('physical_qty');
        $totalReserved = Stock::sum('reserved_qty');
        $totalDamaged = Stock::sum('damaged_qty');
        $totalAvailable = $totalPhysical - $totalReserved - $totalDamaged;
        $lowStockCount = Stock::where('physical_qty', '<=', 10)->count();

        return view('stock.index', compact('stocks', 'totalPhysical', 'totalReserved', 'totalAvailable', 'totalDamaged', 'lowStockCount'));
    }


    public function adjustStock(Request $request)
{



    $request->validate([
        'product_id' => 'required|exists:products,id',
        'adjustment_type' => 'required|in:add,subtract,damage',
        'quantity' => 'required|integer|min:1',
        'note' => 'nullable|string',
    ]);



    try {
        DB::beginTransaction();

        $stock = Stock::firstOrCreate(
            ['product_id' => $request->product_id],
            [
                'physical_qty' => 0,
                'reserved_qty' => 0,
                'damaged_qty' => 0,
                'current_stock' => 0
            ]
        );

        if ($request->adjustment_type == 'add') {
            $stock->physical_qty += $request->quantity;
            $stock->current_stock += $request->quantity;
        } elseif ($request->adjustment_type == 'subtract') {
            $stock->physical_qty = max(0, $stock->physical_qty - $request->quantity);
            $stock->current_stock = max(0, $stock->current_stock - $request->quantity);
        } elseif ($request->adjustment_type == 'damage') {
            $stock->physical_qty = max(0, $stock->physical_qty - $request->quantity);
            $stock->current_stock = max(0, $stock->current_stock - $request->quantity);
            $stock->damaged_qty += $request->quantity;
        }

        $stock->save();

        DB::commit();
        return redirect()->route('stock.index')->with('success', 'Stock successfully adjusted!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

}
