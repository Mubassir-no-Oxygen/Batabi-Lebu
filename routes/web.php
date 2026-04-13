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