<?php

use App\Http\Controllers\Frontend\BlogController;
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

Route::controller(BlogController::class)->prefix('blog')->group(function () {
    Route::get('/', 'index')->name('blog.index');
    Route::get('/{post:slug}', 'show')->name('blog.show');
});


Route::controller(ShopController::class)->group(function () {
    // Products list / search (no category)
    Route::get('shop', 'shopIndex')->name('shop');
    // Category listing (clean slug URL)
    Route::get('shop/{category:slug}', 'shopCategory')->name('shop.category');
    // Product details (clean slug URL)
    Route::get('product/{product:slug}', 'productDetails')->name('productDetails');
});

// Gift box builder (pick 3–5 items).
Route::get('gift-box', [\App\Http\Controllers\Frontend\GiftBoxController::class, 'show'])->name('gift-box');
Route::post('gift-box', [\App\Http\Controllers\Frontend\GiftBoxController::class, 'store'])->name('gift-box.store')->middleware('throttle:20,1');

// Social proof + WhatsApp updates opt-in.
Route::get('recent-orders', [\App\Http\Controllers\Frontend\SocialProofController::class, 'recentOrders'])->name('recent-orders')->middleware('throttle:30,1');
Route::post('subscribe', [\App\Http\Controllers\Frontend\SocialProofController::class, 'subscribe'])->name('subscribe')->middleware('throttle:5,1');

// Customer reviews (held for admin approval).
Route::post('product/{product:slug}/reviews', [\App\Http\Controllers\Frontend\ReviewController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('reviews.store');
// Store-wide reviews page + "Leave a review" form (also held for approval).
Route::get('reviews', [\App\Http\Controllers\Frontend\StoreReviewController::class, 'index'])->name('store-reviews.index');
Route::post('reviews', [\App\Http\Controllers\Frontend\StoreReviewController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('store-reviews.store');

// Instant results for the header search box (debounced client-side, 250ms).
Route::get('search/suggest', \App\Http\Controllers\Frontend\SearchSuggestController::class)
    ->middleware('throttle:90,1')
    ->name('search.suggest');

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
    Route::get('/cart/drawer', 'drawer')->name('cart.drawer');
});

Route::get('checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1');
Route::post('checkout/capture', \App\Http\Controllers\Frontend\CheckoutCaptureController::class)->name('checkout.capture')->middleware('throttle:20,1');
Route::get('order/success/{order_number}', [CheckoutController::class, 'success'])->name('order.success');


Route::view('cart',   'cart')->name('cart');
Route::view('wishlist', 'wishlist')->name('wishlist');

// Payment Routes
Route::get('payment/start/{order_number}', [PaymentController::class, 'start'])->name('payment.start');
Route::match(['get', 'post'], 'payment/callback/{order_number}', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
Route::get('payment/fake/{order_number}', [PaymentController::class, 'fake'])->name('payment.fake');
