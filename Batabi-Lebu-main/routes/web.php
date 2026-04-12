<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\OrderController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\AgreementController;

Route::middleware('auth')->group(function () {

    Route::resource('orders', OrderController::class)
        ->only(['index', 'create', 'store', 'show']);
    Route::post('orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::post('orders/{order}/negotiate', [NegotiationController::class, 'store'])->name('negotiations.store');
    Route::post('orders/{order}/negotiate/accept', [NegotiationController::class, 'accept'])->name('negotiations.accept');
    Route::post('orders/{order}/negotiate/reject', [NegotiationController::class, 'reject'])->name('negotiations.reject');

    Route::get('agreements', [AgreementController::class, 'index'])->name('agreements.index');
    Route::get('agreements/{agreement}', [AgreementController::class, 'show'])->name('agreements.show');
    Route::post('agreements/{agreement}/sign', [AgreementController::class, 'sign'])->name('agreements.sign');
});
// ─── Delivery Routes (add these to your routes/web.php) ───────────────────
use App\Http\Controllers\DeliveryController;

Route::middleware('auth')->group(function () {
    Route::get('deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('orders/{order}/deliveries/create', [DeliveryController::class, 'create'])->name('deliveries.create');
    Route::post('orders/{order}/deliveries', [DeliveryController::class, 'store'])->name('deliveries.store');
    Route::get('deliveries/{delivery}', [DeliveryController::class, 'show'])->name('deliveries.show');
    Route::patch('deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('deliveries.status');
});
