<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    // ক্যাটাগরি লিস্ট ও পেজিনেশন
    public function index()
    {
        $categories = ExpenseCategory::withCount('expenses')->latest()->paginate(15);
        return view('expenses.categories.index', compact('categories'));
    }

    // নতুন ক্যাটাগরি স্টোর করা
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:expense_categories,name',
            'description' => 'nullable|string|max:255',
        ]);

        ExpenseCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Expense category created successfully!');
    }

    // ক্যাটাগরি আপডেট করা
    public function update(Request $request, ExpenseCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:expense_categories,name,' . $category->id,
            'description' => 'nullable|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Expense category updated successfully!');
    }

    // ক্যাটাগরি ডিলিট করা
    public function destroy(ExpenseCategory $category)
    {
        if ($category->expenses()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete this category because it has associated expenses!']);
        }

        $category->delete();
        return back()->with('success', 'Expense category deleted successfully!');
    }
}
