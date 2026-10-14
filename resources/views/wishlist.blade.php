@extends('layouts.guest')

@php
    use App\Models\Wishlist;
    use App\Models\Product;

    $wishlistProductIds = auth()->check()
        ? Wishlist::where('user_id', auth()->id())->pluck('product_id')
        : collect();

    $wishlistProducts = Product::whereIn('id', $wishlistProductIds)->get();
@endphp

@section('content')
<div class="container-shop space-y-8 py-8 sm:space-y-10">

    {{-- Page Header --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-pink-100 via-rose-50 to-purple-100 px-5 py-12 text-center sm:px-10 sm:py-16">
        <div class="pointer-events-none absolute -left-10 -top-10 h-36 w-36 rounded-full bg-pink-200/40 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-12 -right-8 h-40 w-40 rounded-full bg-purple-200/40 blur-2xl"></div>

        <div class="relative mx-auto max-w-2xl">
            <span class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-sm font-semibold text-pink-600 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35 10.55 20C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09A5.97 5.97 0 0 1 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.51L12 21.35Z"/>
                </svg>
                Your Favorites
            </span>

            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                My Wishlist
            </h1>

            <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-gray-600 sm:text-base">
                Keep all your favorite finds in one place. Save the things you love
                and come back whenever you're ready to shop!
            </p>

            <div class="mt-6 flex flex-wrap items-center justify-center gap-3 text-sm font-medium text-gray-600">
                <span class="rounded-full bg-white/80 px-4 py-2">
                    {{ $wishlistProducts->count() }}
                    {{ $wishlistProducts->count() === 1 ? 'favorite item' : 'favorite items' }}
                </span>

                <a href="{{ route('shop') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-pink-500 px-5 py-2.5 font-semibold text-white shadow-sm transition hover:bg-pink-600">
                    Continue Shopping
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Wishlist Products --}}
    <section aria-label="Wishlist products" class="space-y-5">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-pink-500">
                    Saved for later
                </p>
                <h2 class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">
                    Your Favorite Picks
                </h2>
            </div>
        </div>

        @if ($wishlistProducts->isEmpty())
            <div class="rounded-3xl border border-pink-100 bg-white px-5 py-12 text-center shadow-sm sm:py-16">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-pink-50 text-pink-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78Z"/>
                    </svg>
                </div>

                <h3 class="mt-5 text-xl font-bold text-gray-900">
                    Your wishlist is waiting for some love!
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                    You haven't saved any products yet. Explore the store and tap the heart
                    on anything you love to save it here.
                </p>

                <a href="{{ route('shop') }}"
                   class="mt-6 inline-flex items-center justify-center rounded-full bg-pink-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-pink-600">
                    Explore Products
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($wishlistProducts as $product)
                    <article wire:key="wishlist-product-{{ $product->id }}"
                             class="group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                        {{-- Product Image --}}
                        <a href="{{ route('productDetails', $product->id) }}"
                           class="relative block overflow-hidden bg-pink-50">
                            <div class="aspect-square">
                                <img
                                    src="{{ asset('storage/' . $product->product_image) }}"
                                    alt="{{ $product->product_title ?? 'Product image' }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    loading="lazy"
                                >
                            </div>

                            <span class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold text-pink-600 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35 10.55 20C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09A5.97 5.97 0 0 1 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.51L12 21.35Z"/>
                                </svg>
                                Saved
                            </span>
                        </a>

                        {{-- Product Details --}}
                        <div class="flex flex-1 flex-col p-3 sm:p-4">
                            <h3 class="line-clamp-2 text-sm font-bold leading-5 text-gray-900 sm:text-base">
                                <a href="{{ route('productDetails', $product->id) }}"
                                   class="transition hover:text-pink-600">
                                    {{ $product->product_title ?? $product->title ?? 'Untitled Product' }}
                                </a>
                            </h3>

                            @if (!empty($product->product_description))
                                <p class="mt-2 line-clamp-2 text-xs leading-5 text-gray-500 sm:text-sm">
                                    {{ strip_tags($product->product_description) }}
                                </p>
                            @endif

                            <div class="mt-auto space-y-2 pt-4">
                                <a href="{{ route('productDetails', $product->id) }}"
                                   class="flex w-full items-center justify-center rounded-full bg-pink-500 px-3 py-2.5 text-xs font-semibold text-white transition hover:bg-pink-600 sm:text-sm">
                                    View Product
                                </a>

                                <form action="{{ route('wishlist.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <button type="submit"
                                            class="flex w-full items-center justify-center gap-2 rounded-full border border-gray-200 px-3 py-2.5 text-xs font-semibold text-gray-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600 sm:text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12m-10 0 .7 13h6.6L16 7M9 7V4h6v3"/>
                                        </svg>
                                        Remove from Wishlist
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Shared Store Trust Bar --}}
    <x-shop.trust-bar />

</div>
@endsection