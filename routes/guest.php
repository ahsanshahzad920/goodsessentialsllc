<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LlmsTxtController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\PaymentController;

// Route::view('/', 'index')->name('index');
Route::view('about', 'about')->name('about');
Route::view('/privacy-policy', 'policies.privacy-policy')->name('privacy-policy');
Route::view('/return-refund-policy', 'policies.return-refund-policy')->name('return-refund-policy');
Route::view('/shipping-policy', 'policies.shipping-policy')->name('shipping-policy');
Route::view('/terms-and-conditions', 'policies.terms-and-conditions')->name('terms-and-conditions');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/llms.txt', [LlmsTxtController::class, 'index'])->name('llms-txt');


Route::controller(ShopController::class)->group(function () {
    // Products list / search (no category)
    Route::get('shop', 'shopIndex')->name('shop');
    // Category listing (clean slug URL)
    Route::get('shop/{category:slug}', 'shopCategory')->name('shop.category');
    // Product details (clean slug URL)
    Route::get('product/{product:slug}', 'productDetails')->name('productDetails');
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

// Payment Routes
Route::get('payment/start/{order_number}', [PaymentController::class, 'start'])->name('payment.start');
Route::match(['get', 'post'], 'payment/callback/{order_number}', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
Route::get('payment/fake/{order_number}', [PaymentController::class, 'fake'])->name('payment.fake');
