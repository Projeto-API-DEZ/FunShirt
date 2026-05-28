<?php

use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TshirtImageController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ColorController;

// Public Catalog
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{tshirtImage}', [CatalogController::class, 'show'])->name('catalog.show');

// Public Session-Based Shopping Cart 
// FIXED: Removed "value:" parameter that caused the PHP2422 error
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'show'])->name('show');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// Authentication Required Rules
Route::middleware(['auth'])->group(function () {
    
    // Profiles
    Route::get('/profile', [UserManagementController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserManagementController::class, 'updateProfile'])->name('profile.update');

    // Customer Checkout Process
    Route::middleware(['can:create,App\Models\Order'])->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    });

    // Orders Actions
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show')->middleware('can:view,order');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel')->middleware('can:cancel,order');
    });

    // Custom Designs CRUD
    Route::resource('tshirt-images', TshirtImageController::class);

    // Administrative & Staff Configuration
    Route::middleware(['can:admin-access'])->group(function () {
        Route::get('/prices', [PriceController::class, 'index'])->name('prices.index');
        Route::put('/prices', [PriceController::class, 'update'])->name('prices.update');
        Route::resource('colors', ColorController::class)->except(['show']);
        Route::resource('users', UserManagementController::class);
    });
});