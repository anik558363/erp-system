<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // Brand list ebong page view
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view('brands.index', compact('brands'));
    }

    // Notun Brand save kora
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name'
        ]);

        Brand::create([
            'name' => $request->name
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand successfully added.');
    }

    // Brand update kora
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id
        ]);

        $brand->update([
            'name' => $request->name
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand successfully updated.');
    }

    // Brand delete kora
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }
}
