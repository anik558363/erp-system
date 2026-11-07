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
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\PosController; // POS কন্ট্রোলার ইম্পোর্ট
use App\Http\Controllers\PosShiftController;
use App\Http\Controllers\PosRegisterController;





// ১. রুট ইউআরএল ('/') ওপেন করলে সরাসরি লগইন পেজে রিডাইরেক্ট করবে
Route::get('/', function () {
    return redirect()->route('login');
});

// ডিফল্ট ড্যাশবোর্ড (লগইন এবং ভেরিফাইড ইউজারদের জন্য - যা কন্ট্রোলারের মাধ্যমে রোল অনুযায়ী ড্যাশবোর্ড দেখাবে)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'status'])
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

// ২. শুধু 'Owner' এবং 'Office' রোল এই পেজগুলো পাবে (POS সহ)
Route::middleware(['auth', 'role:Owner|Office'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');

    // POS Shift Routes
    Route::get('/pos/shift', [PosShiftController::class, 'index'])->name('pos.shift.index');
    Route::post('/pos/shift/open', [PosShiftController::class, 'openShift'])->name('pos.shift.open');
    Route::post('/pos/shift/close/{shift}', [PosShiftController::class, 'closeShift'])->name('pos.shift.close');

    // POS Terminal
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');

// POS Register CRUD Routes
    Route::get('/pos/registers', [PosRegisterController::class, 'index'])->name('pos.registers.index');
    Route::post('/pos/registers', [PosRegisterController::class, 'store'])->name('pos.registers.store');
    Route::put('/pos/registers/{register}', [PosRegisterController::class, 'update'])->name('pos.registers.update');
    Route::delete('/pos/registers/{register}', [PosRegisterController::class, 'destroy'])->name('pos.registers.destroy');


    // POS Sales History & Invoices Routes
Route::get('/pos/sales', [PosController::class, 'salesHistory'])->name('pos.sales.index');
Route::get('/pos/sales/{sale}', [PosController::class, 'showReceipt'])->name('pos.sales.receipt');
Route::delete('/pos/sales/{sale}', [PosController::class, 'destroySale'])->name('pos.sales.destroy');

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
    Route::get('/customers/{customer}/ledger', [CustomerController::class, 'ledger'])->name('customers.ledger');

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

// ৫. ডেলিভারি ও ফিল্ড কালেকশন ম্যানেজমেন্ট (Owner, Office এবং Delivery রোল ভিত্তিক)
Route::middleware(['auth', 'role:Owner|Office|Delivery'])->group(function () {
    Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery.index');
    Route::post('/delivery/assign', [DeliveryController::class, 'assignOrder'])->name('delivery.assign');
    Route::put('/delivery/status/{order}', [DeliveryController::class, 'updateStatus'])->name('delivery.status.update');
    Route::post('/delivery/reconcile', [DeliveryController::class, 'reconcileCash'])->name('delivery.reconcile');
    Route::post('/delivery-payments', [DeliveryController::class, 'store'])->name('delivery.payments.store');
});

// সাধারণ অথেন্টিকেটেড ইউজার বা অর্ডার ও কাস্টমার পেমেন্ট রাউটসমূহ
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // অর্ডার স্ট্যাটাস ও অ্যাপ্রুভাল আপডেট করার রুট
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status.update');

    // Customer Payments / Due Collection Routes
    Route::get('/customer-payments', [CustomerPaymentController::class, 'index'])->name('customers.payments.index');
    Route::post('/customer-payments', [CustomerPaymentController::class, 'store'])->name('customers.payments.store');
});

require __DIR__.'/auth.php';
