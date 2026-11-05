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
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierPaymentController;


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

    // Brands Routes
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

    // Areas Routes
    Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
    Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
    Route::put('/areas/{area}', [AreaController::class, 'update'])->name('areas.update');
    Route::delete('/areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy');

    // Customers Routes
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');


    // Products Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Suppliers Routes
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');


   Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

    // এই রুটটি যুক্ত করতে হবে:
    Route::put('/purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');

// Supplier Payments Routes
    Route::get('/supplier-payments', [SupplierPaymentController::class, 'index'])->name('supplier.payments.index');
    Route::post('/supplier-payments', [SupplierPaymentController::class, 'store'])->name('supplier.payments.store');

    // Stock Management Route
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock/adjust', [StockController::class, 'adjustStock'])->name('stock.adjust');

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
