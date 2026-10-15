<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Frontend\CheckoutController;

// Route::view('/', 'index')->name('index');
Route::view('about', 'about')->name('about');

Route::controller(ShopController::class)->group(function () {
    // Products List
    Route::get('shop/{category_name?}',  'shopIndex')->name('shop');
    // Product Details
    Route::get('product-details/{id}', 'productDetails')->name('productDetails');
});

Route::post('/wishlist/toggle', [WishlistController::class, 'toggleWishlist'])->name('wishlist.toggle')->middleware('auth');
Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove')->middleware('auth');

Route::controller(IndexController::class)->group(function () {
    Route::get('/', 'indexPage')->name('index');
});

// Contact Us
Route::resource('contacts', ContactController::class);

//Cart Route
Route::controller(CartController::class)->group(function () {
    Route::post('/cart/store', 'store')->name('cart.store');
    Route::post('/increment-session', 'incrementSession')->name('increment.session');
    Route::post('/decrement-session', 'decrementSession')->name('decrement.session');
    Route::post('/cart/remove', 'remove')->name('cart.remove');
});

Route::get('checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1');
Route::get('order/success/{order_number}', [CheckoutController::class, 'success'])->name('order.success');


Route::view('cart',   'cart')->name('cart');
Route::view('wishlist', 'wishlist')->name('wishlist');
