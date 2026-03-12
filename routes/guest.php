<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'index')->name('index');
Route::view('about', 'about')->name('about');

Route::controller(ShopController::class)->group(function () {
    // Products List
    Route::get('shop/{category_name?}',  'shopIndex')->name('shop');
    // Product Details
    Route::get('product-details/{id}', 'productDetails')->name('productDetails');
});

Route::view('checkout', 'checkout')->name('checkout');

Route::post('/wishlist/toggle', [WishlistController::class, 'toggleWishlist'])->name('wishlist.toggle')->middleware('auth');
Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove')->middleware('auth');

Route::controller(IndexController::class)->group(function () {
    Route::get('/', 'indexPage')->name('index');
});



// Contact Us
Route::resource('contacts', ContactController::class);

Route::view('cart',   'cart')->name('cart');
Route::view('wishlist', 'wishlist')->name('wishlist');
