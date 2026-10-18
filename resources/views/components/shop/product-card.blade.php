@props(['product', 'wishlisted' => false])

@php
    // calculateDiscountPrice() is your existing helper.
    $price = (float) str_replace(',', '', (string) ($product->price ?? 0));
    $final = (float) str_replace(',', '', (string) (calculateDiscountPrice($product->price, $product->dis_price) ?? $price));
    $onSale = $final > 0 && $final < $price;

    $badge = null;
    $badgeClass = '';
    if ($onSale) {
        $badge = '-' . round((1 - $final / $price) * 100) . '%';
        $badgeClass = 'bg-blush-600';
    } elseif ($product->created_at?->gt(now()->subDays(14))) {
        $badge = 'New';
        $badgeClass = 'bg-mint-600';
    }

    $image  = $product->product_image ? asset('storage/' . $product->product_image) : asset('images/product-placeholder.svg');
    $url    = route('productDetails', $product->slug);
    $rating = $product->rating ?? null;          // optional: shown only if your products have a rating column
    $reviews = $product->reviews_count ?? null;  // optional
@endphp

<article class="flex flex-col rounded-2xl border border-line bg-white p-2 transition hover:shadow-md">
    <div class="relative overflow-hidden rounded-xl bg-mist-100">
        <a href="{{ $url }}" class="block">
            <img src="{{ $image }}" alt="{{ $product->product_title }}" loading="lazy" class="aspect-square w-full object-cover">
        </a>

        @if ($badge)
            <span class="absolute left-2 top-2 rounded-full {{ $badgeClass }} px-2.5 py-0.5 text-[10px] font-bold text-white">{{ $badge }}</span>
        @endif

        <button type="button"
                class="js-wishlist group absolute right-2 top-2 grid size-8 place-items-center rounded-full bg-white/90 text-muted shadow-sm hover:text-blush-600 aria-pressed:bg-blush-600 aria-pressed:text-white"
                data-product-id="{{ $product->id }}"
                data-url="{{ url('/wishlist/toggle') }}"
                data-login="{{ route('login') }}"
                aria-pressed="{{ $wishlisted ? 'true' : 'false' }}"
                aria-label="Toggle wishlist for {{ $product->product_title }}">
            <x-shop.icon name="heart" class="size-4 group-aria-pressed:fill-current" />
        </button>
    </div>

    <div class="flex flex-1 flex-col px-1.5 pb-1.5 pt-3">
        <h3 class="line-clamp-2 min-h-[2.5rem] font-sans text-[13px] font-bold leading-snug">
            <a href="{{ $url }}">{{ $product->product_title }}</a>
        </h3>

        <p class="mt-2 flex flex-wrap items-baseline gap-x-1.5">
            <span class="text-base font-extrabold">{{ money($final ?: $price) }}</span>
            @if ($onSale)
                <span class="text-[11px] text-muted line-through">{{ money($price) }}</span>
            @endif
        </p>

        <div class="mt-3 flex items-center justify-between">
            <div class="flex items-center gap-0.5">
                @if ($rating)
                    <span class="flex" role="img" aria-label="Rated {{ $rating }} out of 5">
                        @for ($i = 1; $i <= 5; $i++)
                            <x-shop.icon name="star" class="size-2.5 {{ $i <= round($rating) ? 'fill-star text-star' : 'text-line' }}" />
                        @endfor
                    </span>
                    @if ($reviews) <span class="ml-1 text-[10px] text-muted">({{ $reviews }})</span> @endif
                @endif
            </div>

            {{-- Goes to product page (your add-to-cart lives there). --}}
            <a href="{{ $url }}"
               class="grid size-8 place-items-center rounded-full bg-lavender-100 text-brand-500 hover:bg-brand-500 hover:text-white"
               aria-label="View {{ $product->product_title }}">
                <x-shop.icon name="cart" class="size-4" />
            </a>
        </div>
    </div>
</article>
