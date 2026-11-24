{{--
    The one product card for every grid (homepage, shop, related products).
    <x-product-card :product="$p" :wishlisted="true" :priority="$loop->index < 2" />

    Keeps the hooks other code relies on:
      - form[action=cart.store] + data-price / data-sku / data-name -> AddToCart pixel (guest layout)
      - .js-wishlist + data-product-id / data-url / data-login     -> wishlist toggle
    Rating only shows when the query loaded real review data
    (withAvg('reviews as reviews_avg_rating', 'rating') + withCount('reviews')).
--}}
@props(['product', 'wishlisted' => false, 'priority' => false])

@php
    $price = (float) str_replace(',', '', (string) ($product->price ?? 0));
    $final = (float) str_replace(',', '', (string) (calculateDiscountPrice($product->price, $product->dis_price) ?? $price));
    $onSale = $final > 0 && $final < $price;
    $pay = $final ?: $price;

    $image = $product->product_image ? asset('storage/' . $product->product_image) : asset('images/product-placeholder.svg');
    $webp  = \App\Support\WebpImage::url($product->product_image);
    $url   = route('productDetails', $product->slug);

    $rating  = $product->reviews_avg_rating ?? null;
    $reviews = $product->reviews_count ?? null;

    // Products with colour options need a choice first, so the card links to the page.
    $canAddToCart = \Illuminate\Support\Facades\Route::has('cart.store') && empty($product->colors);
@endphp

<article {{ $attributes->class('group relative flex min-w-0 flex-col overflow-hidden rounded-card border border-line bg-white transition-[box-shadow,transform] duration-300 ease-soft hover:shadow-lift') }}>
    <div class="relative bg-frame">
        <a href="{{ $url }}" class="block overflow-hidden" tabindex="-1" aria-hidden="true">
            <picture>
                @if ($webp)
                    <source type="image/webp" srcset="{{ $webp }}">
                @endif
                <img src="{{ $image }}" alt="{{ $product->product_title }}"
                     width="600" height="600" decoding="async"
                     @if ($priority) loading="eager" fetchpriority="high" @else loading="lazy" @endif
                     class="aspect-square w-full object-contain p-3 mix-blend-multiply transition-transform duration-300 ease-soft group-hover:scale-105">
            </picture>
        </a>

        @if ($onSale)
            <x-badge tone="sale" class="absolute left-2.5 top-2.5">-{{ round((1 - $final / $price) * 100) }}%</x-badge>
        @elseif ($product->created_at?->gt(now()->subDays(14)))
            <x-badge tone="new" class="absolute left-2.5 top-2.5">New</x-badge>
        @endif

        <button type="button"
                class="js-wishlist group/heart absolute right-1.5 top-1.5 z-10 grid size-11 place-items-center rounded-full text-muted transition active:scale-90"
                data-product-id="{{ $product->id }}"
                data-url="{{ url('/wishlist/toggle') }}"
                data-login="{{ route('login') }}"
                aria-pressed="{{ $wishlisted ? 'true' : 'false' }}"
                aria-label="Save {{ $product->product_title }} to wishlist">
            <span class="grid size-8 place-items-center rounded-full bg-white/95 shadow-sm group-hover/heart:text-coral-600 group-aria-pressed/heart:bg-coral-600 group-aria-pressed/heart:text-white">
                <x-shop.icon name="heart" class="size-4 group-aria-pressed/heart:fill-current" />
            </span>
        </button>
    </div>

    <div class="flex flex-1 flex-col gap-1.5 p-3 sm:p-3.5">
        <h3 class="line-clamp-2 min-h-[2.6em] font-sans text-[13px] font-bold leading-[1.3] text-ink sm:text-sm">
            <a href="{{ $url }}" class="after:absolute after:inset-0 after:rounded-card after:content-[''] focus-visible:outline-none focus-visible:after:outline-2 focus-visible:after:outline-offset-2 focus-visible:after:outline-brand-600">{{ $product->product_title }}</a>
        </h3>

        <x-rating-stars :rating="$rating" :count="$reviews" size="xs" />

        <x-price :amount="$pay" :original="$onSale ? $price : null" size="sm" :show-save="false" />

        <div class="relative z-10 mt-auto pt-2">
            @if ($canAddToCart)
                <form method="POST" action="{{ route('cart.store') }}"
                      data-price="{{ $pay }}"
                      data-sku="{{ $product->sku ?: $product->id }}"
                      data-name="{{ $product->product_title }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <x-button variant="soft" size="sm" icon="cart" class="w-full px-2 text-center leading-tight whitespace-normal sm:w-full max-[400px]:[&>svg]:hidden">Add to cart</x-button>
                </form>
            @else
                <x-button :href="$url" variant="soft" size="sm" icon="cart" class="w-full px-2 text-center leading-tight whitespace-normal sm:w-full max-[400px]:[&>svg]:hidden">Choose options</x-button>
            @endif
        </div>
    </div>
</article>
