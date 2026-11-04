<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    // Area list ebong page view
    public function index()
    {
        $areas = Area::latest()->paginate(10);
        return view('areas.index', compact('areas'));
    }

    // Notun Area save kora
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:areas,name'
        ]);

        Area::create([
            'name' => $request->name,
            'is_active' => true
        ]);

        return redirect()->route('areas.index')->with('success', 'Area successfully added.');
    }

    // Area update kora
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:areas,name,' . $area->id
        ]);

        $area->update([
            'name' => $request->name
        ]);

        return redirect()->route('areas.index')->with('success', 'Area successfully updated.');
    }

    // Area delete kora
    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area deleted successfully.');
    }
}
