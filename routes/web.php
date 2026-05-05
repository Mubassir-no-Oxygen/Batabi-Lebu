<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

// ─── Public Routes ────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── Authentication Routes ────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Farmer Routes ────────────────────────────────────────────
// Pending/rejected pages are accessible without 'farmer.approved' so farmers can see their status
Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/farmer/pending',  [FarmerController::class, 'pending'])->name('farmer.pending');
    Route::get('/farmer/rejected', [FarmerController::class, 'rejected'])->name('farmer.rejected');
});

// These routes require APPROVED farmer status
Route::middleware(['auth', 'role:farmer', 'farmer.approved'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard',  [FarmerController::class, 'dashboard'])->name('dashboard');

    // Crop management
    Route::get('/crops',              [CropController::class, 'index'])->name('crops.index');
    Route::get('/crops/create',       [CropController::class, 'create'])->name('crops.create');
    Route::post('/crops',             [CropController::class, 'store'])->name('crops.store');
    Route::get('/crops/{crop}/edit',  [CropController::class, 'edit'])->name('crops.edit');
    Route::put('/crops/{crop}',       [CropController::class, 'update'])->name('crops.update');
    Route::delete('/crops/{crop}',    [CropController::class, 'destroy'])->name('crops.destroy');

    // Order management (incoming orders from buyers)
    Route::get('/orders',             [OrderController::class, 'farmerOrders'])->name('orders.index');
    Route::patch('/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::patch('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');
});

// ─── Buyer Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard',          [BuyerController::class, 'dashboard'])->name('dashboard');
    Route::get('/crops',              [BuyerController::class, 'browseCrops'])->name('crops.index');
    Route::get('/crops/{crop}',       [BuyerController::class, 'showCrop'])->name('crops.show');
    Route::post('/crops/{crop}/order',[OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders',             [OrderController::class, 'buyerOrders'])->name('orders.index');
});

// ─── Admin Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                        [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/farmers',                          [AdminController::class, 'farmers'])->name('farmers.index');
    Route::get('/farmers/{farmer}',                 [AdminController::class, 'showFarmer'])->name('farmers.show');
    Route::patch('/farmers/{farmer}/approve',       [AdminController::class, 'approveFarmer'])->name('farmers.approve');
    Route::patch('/farmers/{farmer}/reject',        [AdminController::class, 'rejectFarmer'])->name('farmers.reject');
});
