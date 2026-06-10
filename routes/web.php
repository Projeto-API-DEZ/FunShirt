<?php

use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'home');

Route::get('user-photo/{path}', function (string $path) {
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('user.photo');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Backend Routes
    |--------------------------------------------------------------------------
    |
    | Admin role protection remains enforced in UserManagementController.
    |
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserManagementController::class);

        Route::patch('users/{user}/toggle-block', [UserManagementController::class, 'toggleBlock'])
            ->name('users.toggle-block');
    });
});

require __DIR__.'/auth.php';
