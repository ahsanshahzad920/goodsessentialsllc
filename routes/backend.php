<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\Backend\Category\CategoryController;
use App\Http\Controllers\Backend\Product\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth', 'verified'])
    ->group(function () {

        // Dashboard Route
        Route::view('dashboard', 'backend.dashboard')->name('dashboard');

        // Categories Route
        Route::resource('categories', CategoryController::class);

        // Products Route
        Route::resource('products', ProductController::class);

        // Cart Route
        Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
        Route::post('/increment-session', [CartController::class, 'incrementSession'])->name('increment.session');
        Route::post('/decrement-session', [CartController::class, 'decrementSession'])->name('decrement.session');
        Route::post('/cart/remove', [CartController::class, 'remove']) ->name('cart.remove');
        
        // Profile
        Route::get('profile', [ProfileController::class, 'showProfile'])->name('profile');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    });
