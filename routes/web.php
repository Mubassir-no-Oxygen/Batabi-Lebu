<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FraudReportController;
use App\Http\Controllers\Admin\FraudReportController as AdminFraudReportController;

Route::get('/', function () {
    return view('welcome');
});

// Feature 9: Payments (no auth for testing)
Route::get('/orders/{order}/payment/create',   [PaymentController::class, 'create'])->name('payment.create');
Route::post('/orders/{order}/payment',         [PaymentController::class, 'store'])->name('payment.store');
Route::get('/orders/{order}/payment',          [PaymentController::class, 'show'])->name('payment.show');
Route::post('/orders/{order}/payment/release', [PaymentController::class, 'release'])->name('payment.release');

// Feature 10: Fraud Reports (no auth for testing)
Route::get('/fraud-report/create', [FraudReportController::class, 'create'])->name('fraud.create');
Route::post('/fraud-report',       [FraudReportController::class, 'store'])->name('fraud.store');
Route::get('/fraud-report/mine',   [FraudReportController::class, 'myReports'])->name('fraud.my-reports');

// Admin
Route::get('/admin/fraud-reports',            [AdminFraudReportController::class, 'index'])->name('admin.fraud.index');
Route::patch('/admin/fraud-reports/{report}', [AdminFraudReportController::class, 'update'])->name('admin.fraud.update');


<?php



use App\Http\Controllers\EmergencyRequestController;
use App\Http\Controllers\ProductController;

// ------ FEATURE 11: Emergency Support ------
Route::middleware(['auth'])->group(function () {

    // Farmer routes
    Route::get('/farmer/emergency', [EmergencyRequestController::class, 'index'])
        ->name('emergency.index');
    Route::post('/farmer/emergency', [EmergencyRequestController::class, 'store'])
        ->name('emergency.store');

    // Admin routes
    Route::get('/admin/emergency', [EmergencyRequestController::class, 'adminIndex'])
        ->name('admin.emergency.index');
    Route::post('/admin/emergency/{id}/update', [EmergencyRequestController::class, 'adminUpdate'])
        ->name('admin.emergency.update');
});

// ------ FEATURE 12: Supply Store ------
Route::middleware(['auth'])->group(function () {

    // Farmer: browse store
    Route::get('/store', [ProductController::class, 'index'])
        ->name('store.index');
    Route::get('/store/{id}', [ProductController::class, 'show'])
        ->name('store.show');

    // Supplier: manage products
    Route::get('/supplier/products', [ProductController::class, 'supplierProducts'])
        ->name('supplier.products.index');
    Route::get('/supplier/products/create', [ProductController::class, 'create'])
        ->name('supplier.products.create');
    Route::post('/supplier/products', [ProductController::class, 'store'])
        ->name('supplier.products.store');
    Route::get('/supplier/products/{id}/edit', [ProductController::class, 'edit'])
        ->name('supplier.products.edit');
    Route::put('/supplier/products/{id}', [ProductController::class, 'update'])
        ->name('supplier.products.update');
});
