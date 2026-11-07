<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    // সব খরচ লিস্ট এবং ফিল্টারিং
    public function index(Request $request)
    {
        $categories = ExpenseCategory::all();

        $query = Expense::with(['category', 'recorder'])->latest('expense_date');

        // ক্যাটাগরি ফিল্টার
        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        // তারিখ ফিল্টার
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        $expenses = $query->paginate(15);
        $totalExpense = $query->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'totalExpense'));
    }

    // নতুন খরচ স্টোর বা সেভ করা
    public function store(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'reference_no' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
        ]);

        Expense::create([
            'expense_category_id' => $request->expense_category_id,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'reference_no' => $request->reference_no,
            'note' => $request->note,
            'recorded_by' => Auth::id(),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully!');
    }

    // খরচ ডিলিট করা
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
    }
}
