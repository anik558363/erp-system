<?php

namespace App\Http\Controllers;

use App\Models\PosRegister;
use Illuminate\Http\Request;

class PosRegisterController extends Controller
{
    // রেজিস্টারের তালিকা ও ফর্ম (Read & Create View)
    public function index()
    {
        $registers = PosRegister::latest()->paginate(10);
        return view('pos.registers.index', compact('registers'));
    }

    // নতুন রেজিস্টার সেভ করা (Store)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pos_registers,name',
            'location' => 'nullable|string|max:255',
        ]);

        PosRegister::create([
            'name' => $request->name,
            'location' => $request->location,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('pos.registers.index')->with('success', 'POS Register created successfully!');
    }

    // রেজিস্টার আপডেট করা (Update)
    public function update(Request $request, PosRegister $register)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pos_registers,name,' . $register->id,
            'location' => 'nullable|string|max:255',
        ]);

        $register->update([
            'name' => $request->name,
            'location' => $request->location,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('pos.registers.index')->with('success', 'POS Register updated successfully!');
    }

    // রেজিস্টার ডিলিট করা (Delete)
    public function destroy(PosRegister $register)
    {
        $register->delete();
        return redirect()->route('pos.registers.index')->with('success', 'POS Register deleted successfully!');
    }
}
