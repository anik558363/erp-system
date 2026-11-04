<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// অতিরিক্ত কন্ট্রোলারগুলো ইম্পোর্ট করা হলো
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CategoryController;

// ১. রুট ইউআরএল ('/') ওপেন করলে সরাসরি লগইন পেজে রিডাইরেক্ট করবে
Route::get('/', function () {
    return redirect()->route('login');
});

// ডিফল্ট ড্যাশবোর্ড (লগইন এবং ভেরিফাইড ইউজারদের জন্য - যা কন্ট্রোলারের মাধ্যমে রোল অনুযায়ী ড্যাশবোর্ড দেখাবে)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'status']) // 'status' ekhane add kora holo
    ->name('dashboard');

// প্রোফাইল ম্যানেজমেন্ট রাউট
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==========================================
// ERP SYSTEM ROLE & PERMISSION ROUTES
// ==========================================

// ২. শুধু 'Owner' এবং 'Office' রোল এই পেজগুলো পাবে
Route::middleware(['auth', 'role:Owner|Office'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');


// Category Management Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// ৩. শুধু 'Warehouse' রোল স্টক এবং প্যাকিং ম্যানেজ করবে
Route::middleware(['auth', 'role:Warehouse'])->group(function () {
    Route::get('/warehouse/stock', [StockController::class, 'index'])->name('warehouse.stock');
    Route::post('/orders/pack/{id}', [OrderController::class, 'packOrder'])->name('orders.pack');
});

// ৪. শুধু নির্দিষ্ট পারমিশন (যেমন: 'approve-orders') থাকলে তবেই কাজ করবে
Route::middleware(['auth', 'permission:approve-orders'])->group(function () {
    Route::post('/orders/{id}/approve', [OrderController::class, 'approve'])->name('orders.approve');
});

// ৫. শুধু 'Delivery' ম্যান ডেলিভারি স্ট্যাটাস আপডেট করতে পারবে
Route::middleware(['auth', 'role:Delivery'])->group(function () {
    Route::get('/delivery/assigned', [DeliveryController::class, 'assignedList'])->name('delivery.assigned');
    Route::post('/delivery/update-status/{id}', [DeliveryController::class, 'updateStatus'])->name('delivery.update-status');
});

require __DIR__.'/auth.php';
