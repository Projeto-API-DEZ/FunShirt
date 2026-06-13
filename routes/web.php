<?php

use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\TshirtImageController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomTshirtController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Route::view('/', 'home');

// Public Catalog
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{tshirtImage}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/customize', [CustomTshirtController::class, 'create'])->name('customize.create');
Route::post('/customize', [CustomTshirtController::class, 'store'])->name('customize.store');

// Public Session-Based Shopping Cart (accessible by guests)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'show'])->name('show');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

Route::get('user-photo/{path}', function (string $path) {
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('user.photo');

Route::get('public-storage/{path}', function (string $path) {
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('public.storage');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify-pending', [App\Http\Controllers\AuthController::class, 'showVerificationNotice'])->name('email.verify.notice');
    Route::post('/email/verify-notification', [App\Http\Controllers\AuthController::class, 'resendVerificationNotice'])->name('email.verify.send');

    Route::view('/password', 'profile-password')->name('profile.password');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Customer Checkout Process
    Route::middleware(['can:create,App\Models\Order'])->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    });

    // Receipt Download - Owner & Admin
    Route::get('/orders/{order}/receipt', [ReceiptController::class, 'download'])->name('orders.receipt');


    // Orders Actions
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show')->middleware('can:view,order');
        Route::get('/{order}/items/{item}/image', [OrderController::class, 'previewItemImage'])->name('items.image')->middleware('can:view,order');
        Route::get('/{order}/items/{item}/download', [OrderController::class, 'downloadItemImage'])->name('items.download')->middleware('can:view,order');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel')->middleware('can:cancel,order');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Backend Routes
    |--------------------------------------------------------------------------
    |
    | Admin role protection remains enforced in UserManagementController.
    |
    */
    Route::prefix('admin')->name('admin.')->middleware(['can:admin-access'])->group(function () {
        Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');

        Route::get('/prices', [PriceController::class, 'edit'])->name('prices.index');
        Route::put('/prices', [PriceController::class, 'update'])->name('prices.update');

        Route::resource('colors', ColorController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('tshirt-images', TshirtImageController::class)->except(['show']);

        Route::resource('users', UserManagementController::class);

        
        // Cart routes (accessible by guests and authenticated users)
        Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{tshirtImage}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
        Route::match(['put', 'patch'], '/cart/update/{key}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{key}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
        Route::get('/cart/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

        Route::patch('users/{user}/toggle-block', [UserManagementController::class, 'toggleBlock'])
            ->name('users.toggle-block');

        // Cancel order Admin
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel')->middleware('can:cancel,order');
    
        // Statistics Access
        Route::get('/statistics', [App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('statistics.index');
    });
});

require __DIR__.'/auth.php';
