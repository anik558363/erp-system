<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'stock']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'sku' => 'required|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
            'unit_type' => 'required|string',
            'purchase_price' => 'required|numeric',
            'wholesale_price' => 'required|numeric',
            'mrp' => 'required|numeric',
            'opening_stock' => 'required|integer',
            'min_stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'unit_type' => $request->unit_type,
            'purchase_price' => $request->purchase_price,
            'wholesale_price' => $request->wholesale_price,
            'mrp' => $request->mrp,
            'min_stock' => $request->min_stock,
            'image' => $imagePath,
            'status' => $request->status ?? 'ACTIVE',
        ]);

        // Initial Stock Entry
        if ($request->opening_stock > 0) {
            \App\Models\Stock::updateOrCreate(
                ['product_id' => $product->id],
                ['current_stock' => $request->opening_stock]
            );
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'unit_type' => 'required|string',
            'purchase_price' => 'required|numeric',
            'wholesale_price' => 'required|numeric',
            'mrp' => 'required|numeric',
            'min_stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'unit_type' => $request->unit_type,
            'purchase_price' => $request->purchase_price,
            'wholesale_price' => $request->wholesale_price,
            'mrp' => $request->mrp,
            'min_stock' => $request->min_stock,
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
