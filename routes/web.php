<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

// ─── AUTHENTICATION ROUTES ────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return view('welcome');
});

// Since we are skipping login for this rapid preview MVP, I am mocking Auth by just passing routes without strict auth middleware grouping, or we can use a mock login route. 
// For now, these routes represent Features 3 and 4.

// Feature 3: Farmer Crop Management & Farm Central
Route::prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [FarmerController::class, 'dashboard'])->name('dashboard');
    Route::get('/crops', [CropController::class, 'index'])->name('crops.index');
    Route::get('/crops/create', [CropController::class, 'create'])->name('crops.create');
    Route::post('/crops', [CropController::class, 'store'])->name('crops.store');
    Route::get('/crops/{crop}/edit', [CropController::class, 'edit'])->name('crops.edit');
    Route::put('/crops/{crop}', [CropController::class, 'update'])->name('crops.update');
    Route::delete('/crops/{crop}', [CropController::class, 'destroy'])->name('crops.destroy');

    // Orders for Farmer
    Route::get('/orders', [OrderController::class, 'farmerOrders'])->name('orders.index');
    Route::post('/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');

    // NEW SIDEBAR FEATURES:
    // Weather Alerts & Advisories
    Route::get('/advisories', [\App\Http\Controllers\Farmer\AdvisoryController::class, 'index'])->name('advisories.index');
    
    // Emergency Support
    Route::get('/emergency', [\App\Http\Controllers\Farmer\EmergencyRequestController::class, 'index'])->name('emergency.index');
    Route::post('/emergency', [\App\Http\Controllers\Farmer\EmergencyRequestController::class, 'store'])->name('emergency.store');
    
    // Buy Supplies
    Route::get('/products', [\App\Http\Controllers\Farmer\ProductController::class, 'index'])->name('products.index');
});

// Feature 4: Buyer Browsing and Bulk Orders
Route::prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard', [BuyerController::class, 'dashboard'])->name('dashboard');
    Route::get('/crops', [BuyerController::class, 'browseCrops'])->name('crops.index');
    Route::get('/crops/{crop}', [BuyerController::class, 'showCrop'])->name('crops.show');
    
    // Placing Orders
    Route::post('/orders/{crop}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'buyerOrders'])->name('orders.index');
});

