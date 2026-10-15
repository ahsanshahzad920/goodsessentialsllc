<?php

use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Backend\Category\CategoryController;
use App\Http\Controllers\Backend\Order\OrderController;
use App\Http\Controllers\Backend\Product\ProductController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Role-aware landing: route('dashboard') still works everywhere.
| Admin -> /admin/dashboard, customer -> /account/orders
|--------------------------------------------------------------------------
*/
Route::get('dashboard', DashboardRedirectController::class)
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN AREA  (/admin/...)  - admins only
| Route NAMES are unchanged (products.index, categories.create, ...), only URLs gain /admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'area:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::view('dashboard', 'backend.dashboard')->name('admin.dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);

        // Admin profile
        Route::get('profile', [ProfileController::class, 'showProfile'])->name('profile');
        Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| CUSTOMER AREA  (/account/...)  - customers only (admins are redirected away)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'area:customer'])
    ->prefix('account')
    ->group(function () {

        Route::get('orders', [AccountOrderController::class, 'index'])->name('account.orders.index');
        Route::get('orders/{order}', [AccountOrderController::class, 'show'])->name('account.orders.show');

        Route::get('profile', [ProfileController::class, 'edit'])->name('user.profile.show');
        Route::put('profile', [ProfileController::class, 'updateUserProfile'])->name('user.profile.update');
    });
