<?php

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

require __DIR__.'/auth.php';
