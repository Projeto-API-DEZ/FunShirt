<?php

use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::get('user-photo/{path}', function (string $path) {
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('user.photo');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->prefix('admin/users')->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])
        ->name('admin.users.index');

    Route::get('/create', [UserManagementController::class, 'create'])
        ->name('admin.users.create');

    Route::post('/', [UserManagementController::class, 'store'])
        ->name('admin.users.store');

    Route::get('/{user}', [UserManagementController::class, 'show'])
        ->name('admin.users.show');

    Route::get('/{user}/edit', [UserManagementController::class, 'edit'])
        ->name('admin.users.edit');

    Route::put('/{user}', [UserManagementController::class, 'update'])
        ->name('admin.users.update');

    Route::delete('/{user}', [UserManagementController::class, 'destroy'])
        ->name('admin.users.destroy');

    Route::patch('/{user}/toggle-block', [UserManagementController::class, 'toggleBlock'])
        ->name('admin.users.toggle-block');
});

require __DIR__.'/auth.php';
