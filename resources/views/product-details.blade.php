@extends('layouts.guest')

@php
    use App\Models\Wishlist;
    use Illuminate\Support\Facades\Auth;

    // Fetch wishlist products once for the related products section.
    $wishlistIds = Auth::check()
        ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->all()
        : [];

    // Exclude the current product from related products.
    $relatedProducts = $products->where('id', '!=', $product->id)->take(6);

    // Check whether the product is already in the cart.
    $cart = session('cart', []);
    $isInCart = array_key_exists($product->id, $cart);

    $currentPrice = calculateDiscountPrice(
        $product->price,
        $product->dis_price
    ) ?? 0;
@endphp

@section('content')
<div class="container-shop space-y-10 py-8">

    {{-- Breadcrumbs --}}
    <nav aria-label="Breadcrumb" class="text-sm text-muted">
        <ol class="flex flex-wrap items-center gap-2">
            <li>
                <a href="{{ route('shop') }}"
                   class="transition hover:text-gray-900">
                    Shop
                </a>
            </li>
            <li aria-hidden="true">/</li>
            <li class="font-medium text-gray-800">
                {{ $product->product_title ?? 'Product Details' }}
            </li>
        </ol>
    </nav>

    {{-- Product details --}}
    <section aria-label="Product details"
             class="grid grid-cols-1 items-start gap-8 md:grid-cols-2 lg:gap-12">

        {{-- Product image --}}
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white p-4 sm:p-6">
            <div class="flex min-h-[320px] items-center justify-center rounded-2xl bg-gray-50 p-4 sm:min-h-[460px]">
                <img
                    src="{{ asset('storage/' . $product->product_image) }}"
                    alt="{{ $product->product_title ?? 'Product image' }}"
                    class="max-h-[440px] w-full object-contain"
                    fetchpriority="high"
                >
            </div>
        </div>

        {{-- Product information --}}
        <div class="space-y-5 py-2">

            {{-- Category --}}
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center rounded-full bg-pink-50 px-4 py-2 text-xs font-semibold text-pink-700">
                    {{ $product->category->name ?? 'Kids Collection' }}
                </span>

                @if ($product->dis_price && $product->price > 0)
                    <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                        Special Price
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900 sm:text-4xl">
                {{ $product->product_title ?? 'Product Details' }}
            </h1>

            {{-- Price --}}
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-3xl font-extrabold text-gray-900">
                    ${{ number_format((float) $currentPrice, 2) }}
                </span>

                @if ($product->dis_price && $product->price > $currentPrice)
                    <span class="text-lg text-gray-400 line-through">
                        ${{ number_format((float) $product->price, 2) }}
                    </span>
                @endif
            </div>

            <p class="text-sm leading-6 text-muted">
                A lovely addition to your little one's collection.
                Explore the product details below and add it to your cart.
            </p>

            {{-- Product description --}}
            <div class="border-t border-gray-100 pt-5">
                <h2 class="mb-3 text-lg font-bold text-gray-900">
                    Product Description
                </h2>

                <div class="product-description text-sm leading-7 text-gray-600">
                    {!! $product->product_description ?? '' !!}
                </div>
            </div>

            {{-- Add to cart --}}
            <form action="{{ route('cart.store') }}" method="POST"
                  class="space-y-5 border-t border-gray-100 pt-5">
                @csrf

                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div>
                    <label for="quantityInput"
                           class="mb-3 block text-sm font-semibold text-gray-800">
                        Quantity
                    </label>

                    <div class="inline-flex items-center overflow-hidden rounded-full border border-gray-200 bg-white">
                        <button
                            type="button"
                            id="minusBtn"
                            aria-label="Decrease quantity"
                            class="flex h-12 w-12 items-center justify-center text-lg text-gray-700 transition hover:bg-pink-50 hover:text-pink-600"
                        >
                            &minus;
                        </button>

                        <input
                            type="number"
                            name="quantity"
                            id="quantityInput"
                            value="1"
                            min="1"
                            step="1"
                            required
                            inputmode="numeric"
                            class="h-12 w-14 border-0 bg-transparent p-0 text-center font-semibold text-gray-900 focus:outline-none focus:ring-0"
                        >

                        <button
                            type="button"
                            id="plusBtn"
                            aria-label="Increase quantity"
                            class="flex h-12 w-12 items-center justify-center text-lg text-gray-700 transition hover:bg-pink-50 hover:text-pink-600"
                        >
                            &plus;
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    @if ($isInCart)
                        <button
                            type="button"
                            disabled
                            class="inline-flex min-h-12 flex-1 cursor-not-allowed items-center justify-center gap-2 rounded-full bg-gray-200 px-6 py-3 text-sm font-bold text-gray-500"
                        >
                            <i class="fa-solid fa-check"></i>
                            Already in Cart
                        </button>
                    @else
                        <button
                            type="submit"
                            class="inline-flex min-h-12 flex-1 items-center justify-center gap-2 rounded-full bg-pink-500 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-300"
                        >
                            <i class="fa-solid fa-bag-shopping"></i>
                            Add to Cart
                        </button>
                    @endif

                    <a
                        href="{{ route('shop') }}"
                        class="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:border-pink-300 hover:text-pink-600"
                    >
                        Continue Shopping
                    </a>
                </div>
            </form>

            {{-- Store benefits --}}
            <div class="grid grid-cols-1 gap-3 border-t border-gray-100 pt-5 sm:grid-cols-2">
                <div class="flex items-center gap-3 rounded-2xl bg-pink-50/70 p-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-pink-600">
                        <i class="fa-solid fa-heart"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Made for Little Ones</p>
                        <p class="text-xs text-gray-500">Find something special</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-2xl bg-purple-50/70 p-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-purple-600">
                        <i class="fa-solid fa-gift"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Perfect for Gifting</p>
                        <p class="text-xs text-gray-500">Little things, big smiles</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Related products --}}
    <section aria-label="Related products" class="space-y-5 pt-4">

        <x-shop.section-heading
            title="You May Also Like"
            :heart="true"
            link-text="View All Products"
            :href="route('shop')"
        />

        @if ($relatedProducts->isNotEmpty())
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($relatedProducts as $relatedProduct)
                    <x-shop.product-card
                        :product="$relatedProduct"
                        :wishlisted="in_array($relatedProduct->id, $wishlistIds)"
                    />
                @endforeach
            </div>
        @else
            <div class="rounded-3xl border border-gray-100 bg-white px-6 py-12 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-pink-50 text-pink-500">
                    <i class="fa-solid fa-heart text-xl"></i>
                </div>

                <h3 class="font-bold text-gray-900">
                    More goodies coming soon!
                </h3>

                <p class="mt-2 text-sm text-gray-500">
                    Explore our shop to discover more products.
                </p>

                <a href="{{ route('shop') }}"
                   class="mt-5 inline-flex rounded-full bg-pink-500 px-6 py-3 text-sm font-bold text-white transition hover:bg-pink-600">
                    Explore Shop
                </a>
            </div>
        @endif
    </section>

    {{-- Trust bar from the new theme --}}
    <x-shop.trust-bar />

</div>
@endsection

@push('style')
<style>
    .product-description {
        overflow-wrap: anywhere;
    }

    .product-description img {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
    }

    .product-description ul {
        padding-left: 1.5rem;
        list-style: disc;
    }

    .product-description ol {
        padding-left: 1.5rem;
        list-style: decimal;
    }

    .product-description p + p {
        margin-top: 0.75rem;
    }
</style>
@endpush

@push('script')
<script>
    (() => {
        const quantityInput = document.getElementById('quantityInput');
        const plusBtn = document.getElementById('plusBtn');
        const minusBtn = document.getElementById('minusBtn');

        if (!quantityInput || !plusBtn || !minusBtn) {
            return;
        }

        plusBtn.addEventListener('click', () => {
            const currentValue = Math.max(
                1,
                parseInt(quantityInput.value, 10) || 1
            );

            quantityInput.value = currentValue + 1;
        });

        minusBtn.addEventListener('click', () => {
            const currentValue = Math.max(
                1,
                parseInt(quantityInput.value, 10) || 1
            );

            quantityInput.value = Math.max(1, currentValue - 1);
        });

        quantityInput.addEventListener('change', () => {
            const currentValue = parseInt(quantityInput.value, 10);

            quantityInput.value = Number.isFinite(currentValue)
                ? Math.max(1, currentValue)
                : 1;
        });
    })();
</script>
@endpush
