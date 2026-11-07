<?php

namespace App\Http\Controllers;

use App\Models\PosRegister;
use App\Models\PosShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosShiftController extends Controller
{
    // শিফট ওপেনিং পেজ বা চেক করা
    public function index()
    {
        $user = auth()->user();

        // বর্তমান ইউজারের কোনো ওপেন শিফট আছে কি না চেক করা
        $activeShift = PosShift::with('register')
            ->where('cashier_id', $user->id)
            ->where('status', 'OPEN')
            ->first();

        $registers = PosRegister::where('is_active', true)->get();

        return view('pos.shift', compact('activeShift', 'registers'));
    }

    // শিফট ওপেন করা (Store Shift)
    public function openShift(Request $request)
    {
        $request->validate([
            'register_id' => 'required|exists:pos_registers,id',
            'opening_cash' => 'required|numeric|min:0',
        ]);

        // চেক করা যে এই ইউজারের ইতিমধ্যে কোনো ওপেন শিফট আছে কিনা
        $existingShift = PosShift::where('cashier_id', auth()->id())
            ->where('status', 'OPEN')
            ->first();

        if ($existingShift) {
            return redirect()->route('pos.index')->with('success', 'You already have an active shift!');
        }

        PosShift::create([
            'register_id' => $request->register_id,
            'cashier_id' => auth()->id(),
            'opened_at' => now(),
            'opening_cash' => $request->opening_cash,
            'status' => 'OPEN',
            'note' => $request->note,
        ]);

        return redirect()->route('pos.index')->with('success', 'POS Shift opened successfully. Happy Selling!');
    }

    // শিফট ক্লোজ করা (Close Shift)
    public function closeShift(Request $request, PosShift $shift)
    {
        $request->validate([
            'counted_cash' => 'required|numeric|min:0',
        ]);

        // এই শিফটের মোট ক্যাশ সেলস হিসাব করা
        $totalCashSales = $shift->sales()
            ->join('pos_payments', 'pos_sales.id', '=', 'pos_payments.sale_id')
            ->where('pos_payments.method', 'CASH')
            ->sum('pos_payments.amount');

        $expectedCash = $shift->opening_cash + $totalCashSales;
        $countedCash = (float) $request->counted_cash;
        $difference = $countedCash - $expectedCash;

        $shift->update([
            'closed_at' => now(),
            'expected_cash' => $expectedCash,
            'counted_cash' => $countedCash,
            'difference' => $difference,
            'status' => 'CLOSED',
            'closed_by' => auth()->id(),
            'note' => $request->note,
        ]);

        return redirect()->route('pos.shift.index')->with('success', 'POS Shift closed successfully!');
    }
}
