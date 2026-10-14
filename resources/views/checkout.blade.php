@extends('layouts.guest')

@php
    $cart = session('cart', []);

    $subtotal = collect($cart)->sum(function ($details) {
        return ($details['quantity'] ?? 0) * ($details['product_price'] ?? 0);
    });

    $shipping = empty($cart) ? 0 : 50;
    $total = $subtotal + $shipping;
@endphp

@section('content')
<div class="container-shop space-y-8 py-8">

    {{-- Page heading --}}
    <section class="relative overflow-hidden rounded-3xl bg-pink-50 px-6 py-10 sm:px-10 sm:py-14">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-pink-600 shadow-sm">
                <i class="fa-solid fa-heart"></i>
                Almost yours!
            </span>

            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Checkout
            </h1>

            <p class="mt-3 max-w-xl text-sm leading-7 text-gray-600 sm:text-base">
                You're just a few steps away from something special.
                Review your order and enter your delivery details below.
            </p>
        </div>

        <div class="pointer-events-none absolute -right-6 -top-10 hidden h-56 w-56 rounded-full bg-pink-100 sm:block"></div>
        <div class="pointer-events-none absolute -bottom-16 right-28 hidden h-36 w-36 rounded-full bg-purple-100 sm:block"></div>
    </section>

    {{-- Breadcrumbs --}}
    <nav aria-label="Breadcrumb" class="text-sm text-muted">
        <ol class="flex items-center gap-2">
            <li>
                <a href="{{ route('shop') }}" class="transition hover:text-pink-600">
                    Shop
                </a>
            </li>
            <li aria-hidden="true">/</li>
            <li>
                <a href="{{ route('cart') }}" class="transition hover:text-pink-600">
                    Cart
                </a>
            </li>
            <li aria-hidden="true">/</li>
            <li class="font-semibold text-gray-800">Checkout</li>
        </ol>
    </nav>

    @if (empty($cart))
        {{-- Empty cart --}}
        <section class="rounded-3xl border border-gray-100 bg-white px-6 py-14 text-center shadow-sm">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-pink-50 text-pink-500">
                <i class="fa-solid fa-bag-shopping text-3xl"></i>
            </div>

            <h2 class="mt-5 text-2xl font-bold text-gray-900">
                Your cart is waiting for some goodies!
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Add something lovely to your cart before checking out.
            </p>

            <a href="{{ route('shop') }}"
               class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-pink-500 px-7 py-3 font-bold text-white transition hover:bg-pink-600">
                <i class="fa-solid fa-store"></i>
                Explore the Shop
            </a>
        </section>
    @else

        <form action="#" method="get" id="checkoutForm">
            @csrf

            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

                {{-- Billing details --}}
                <section class="space-y-6 lg:col-span-2">

                    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm sm:p-8">
                        <div class="mb-6 flex items-center gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-pink-50 text-pink-600">
                                <i class="fa-solid fa-user text-lg"></i>
                            </div>

                            <div>
                                <h2 class="text-xl font-bold text-gray-900">
                                    Billing Details
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    Tell us where to deliver your order.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="first_name" class="mb-2 block text-sm font-semibold text-gray-700">
                                    First Name <span class="text-pink-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="first_name"
                                    id="first_name"
                                    value="{{ old('first_name', Auth::user()->first_name ?? '') }}"
                                    autocomplete="given-name"
                                    required
                                    class="checkout-input"
                                    placeholder="Enter first name"
                                >
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Last Name <span class="text-pink-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="last_name"
                                    id="last_name"
                                    value="{{ old('last_name', Auth::user()->last_name ?? '') }}"
                                    autocomplete="family-name"
                                    required
                                    class="checkout-input"
                                    placeholder="Enter last name"
                                >
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Phone Number <span class="text-pink-500">*</span>
                                </label>
                                <input
                                    type="tel"
                                    name="phone"
                                    id="phone"
                                    value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                    autocomplete="tel"
                                    required
                                    class="checkout-input"
                                    placeholder="Enter phone number"
                                >
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Email Address <span class="text-pink-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email', Auth::user()->email ?? '') }}"
                                    autocomplete="email"
                                    required
                                    class="checkout-input"
                                    placeholder="example@email.com"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="address" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Street Address <span class="text-pink-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="address"
                                    id="address"
                                    value="{{ old('address') }}"
                                    autocomplete="street-address"
                                    required
                                    class="checkout-input"
                                    placeholder="House number and street name"
                                >
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="mb-2 block text-sm font-semibold text-gray-700">
                                    City <span class="text-pink-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="city"
                                    id="city"
                                    value="{{ old('city') }}"
                                    autocomplete="address-level2"
                                    required
                                    class="checkout-input"
                                    placeholder="Enter city"
                                >
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="zip_code" class="mb-2 block text-sm font-semibold text-gray-700">
                                    ZIP / Postal Code <span class="text-pink-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="zip_code"
                                    id="zip_code"
                                    value="{{ old('zip_code') }}"
                                    autocomplete="postal-code"
                                    required
                                    class="checkout-input"
                                    placeholder="Enter postal code"
                                >
                                @error('zip_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="notes" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Additional Notes
                                    <span class="font-normal text-gray-400">(Optional)</span>
                                </label>
                                <textarea
                                    name="notes"
                                    id="notes"
                                    rows="4"
                                    class="checkout-input resize-y"
                                    placeholder="Delivery instructions or any special requests..."
                                >{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Checkout reassurance --}}
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="flex items-center gap-3 rounded-2xl bg-white p-4">
                            <i class="fa-solid fa-lock text-lg text-pink-500"></i>
                            <span class="text-xs font-semibold text-gray-700">Secure Checkout</span>
                        </div>

                        <div class="flex items-center gap-3 rounded-2xl bg-white p-4">
                            <i class="fa-solid fa-box text-lg text-purple-500"></i>
                            <span class="text-xs font-semibold text-gray-700">Careful Packaging</span>
                        </div>

                        <div class="flex items-center gap-3 rounded-2xl bg-white p-4">
                            <i class="fa-solid fa-heart text-lg text-rose-500"></i>
                            <span class="text-xs font-semibold text-gray-700">Made with Love</span>
                        </div>
                    </div>

                </section>

                {{-- Order summary --}}
                <aside class="lg:sticky lg:top-6">
                    <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">

                        <div class="bg-pink-50 px-5 py-5 sm:px-6">
                            <h2 class="text-xl font-bold text-gray-900">
                                Your Order
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ count($cart) }} {{ count($cart) === 1 ? 'product' : 'products' }} in your cart
                            </p>
                        </div>

                        <div class="space-y-4 p-5 sm:p-6">

                            {{-- Cart items --}}
                            <div class="max-h-80 space-y-4 overflow-y-auto">
                                @foreach ($cart as $id => $details)
                                    @php
                                        $quantity = (int) ($details['quantity'] ?? 0);
                                        $price = (float) ($details['product_price'] ?? 0);
                                        $productTotal = $quantity * $price;
                                    @endphp

                                    <div class="flex items-start gap-3">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-pink-50 text-pink-400">
                                            <i class="fa-solid fa-bag-shopping"></i>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="line-clamp-2 text-sm font-semibold text-gray-800">
                                                {{ $details['product_title'] ?? 'Product' }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Qty: {{ $quantity }} × ${{ number_format($price, 2) }}
                                            </p>
                                        </div>

                                        <span class="shrink-0 text-sm font-bold text-gray-900">
                                            ${{ number_format($productTotal, 2) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-dashed border-gray-200"></div>

                            {{-- Totals --}}
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between gap-3 text-gray-600">
                                    <span>Subtotal</span>
                                    <span class="font-semibold text-gray-900">
                                        ${{ number_format($subtotal, 2) }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-3 text-gray-600">
                                    <span>Shipping</span>
                                    <span class="font-semibold text-gray-900">
                                        ${{ number_format($shipping, 2) }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-3 border-t border-gray-100 pt-4">
                                    <span class="text-base font-bold text-gray-900">Total</span>
                                    <span class="text-2xl font-extrabold text-pink-600">
                                        ${{ number_format($total, 2) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Terms --}}
                            <div class="rounded-2xl bg-gray-50 p-4">
                                <label for="terms" class="flex cursor-pointer items-start gap-3">
                                    <input
                                        type="checkbox"
                                        id="terms"
                                        name="terms"
                                        value="1"
                                        required
                                        class="mt-1 h-4 w-4 rounded border-gray-300 text-pink-500 focus:ring-pink-400"
                                    >
                                    <span class="text-xs leading-5 text-gray-600">
                                        I have read and accept the
                                        <a href="#"
                                           class="font-semibold text-pink-600 underline">
                                            Terms & Conditions
                                        </a>.
                                    </span>
                                </label>
                                @error('terms')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Payment button --}}
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-full bg-pink-500 px-5 py-4 text-sm font-bold text-white shadow-sm transition hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-300"
                            >
                                Continue to Payment
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>

                            <a href="{{ route('shop') }}"
                               class="flex w-full items-center justify-center gap-2 py-2 text-sm font-semibold text-gray-500 transition hover:text-pink-600">
                                <i class="fa-solid fa-arrow-left"></i>
                                Continue Shopping
                            </a>

                        </div>
                    </div>
                </aside>

            </div>
        </form>
    @endif

</div>
@endsection

@push('style')
<style>
    .checkout-input {
        display: block;
        width: 100%;
        min-height: 48px;
        border: 1px solid #e5e7eb;
        border-radius: 0.875rem;
        background-color: #fff;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: #111827;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .checkout-input::placeholder {
        color: #9ca3af;
    }

    .checkout-input:focus {
        outline: none;
        border-color: #ec4899;
        box-shadow: 0 0 0 3px rgb(236 72 153 / 12%);
    }

    textarea.checkout-input {
        min-height: 110px;
    }
</style>
@endpush