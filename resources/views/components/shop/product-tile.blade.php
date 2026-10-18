@props(['product', 'wishlisted' => false])

@php
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

    $image   = $product->product_image ? asset('storage/' . $product->product_image) : asset('images/product-placeholder.svg');
    $url     = route('productDetails', $product->slug);
    $rating  = $product->rating ?? null;
    $reviews = $product->reviews_count ?? null;

    $canAddToCart = \Illuminate\Support\Facades\Route::has('cart.store') && empty($product->colors);
    $btn = 'flex w-full items-center justify-center gap-1.5 rounded-full border border-brand-500/30 bg-lavender-100 py-2.5 text-[11px] font-extrabold text-brand-500 transition hover:bg-brand-500 hover:text-white';
@endphp

<article class="group flex flex-col overflow-hidden rounded-2xl border border-line bg-white shadow-sm transition hover:shadow-md">
    <div class="relative bg-mist-100">
        <a href="{{ $url }}" class="block overflow-hidden">
            <img src="{{ $image }}" alt="{{ $product->product_title }}" loading="lazy"
                 class="aspect-square w-full object-cover transition duration-300 group-hover:scale-105">
        </a>

        @if ($badge)
            <span class="absolute left-3 top-3 rounded-full {{ $badgeClass }} px-2.5 py-0.5 text-[10px] font-bold text-white">{{ $badge }}</span>
        @endif

        <button type="button"
                class="js-wishlist group/heart absolute right-3 top-3 grid size-8 place-items-center rounded-full bg-white/90 text-muted shadow-sm hover:text-blush-600 aria-pressed:bg-blush-600 aria-pressed:text-white"
                data-product-id="{{ $product->id }}"
                data-url="{{ url('/wishlist/toggle') }}"
                data-login="{{ route('login') }}"
                aria-pressed="{{ $wishlisted ? 'true' : 'false' }}"
                aria-label="Toggle wishlist for {{ $product->product_title }}">
            <x-shop.icon name="heart" class="size-4 group-aria-pressed/heart:fill-current" />
        </button>
    </div>

    <div class="flex flex-1 flex-col p-4">
        <h3 class="line-clamp-2 min-h-[2.5rem] font-sans text-[13px] font-bold leading-snug">
            <a href="{{ $url }}">{{ $product->product_title }}</a>
        </h3>

        <p class="mt-3 flex flex-wrap items-baseline gap-x-1.5">
            <span class="text-[15px] font-extrabold">{{ money($final ?: $price) }}</span>
            @if ($onSale)
                <span class="text-[11px] text-muted line-through">{{ money($price) }}</span>
            @endif
        </p>

        <div class="mt-1.5 flex h-4 items-center gap-1">
            @if ($rating)
                <span class="flex" role="img" aria-label="Rated {{ $rating }} out of 5">
                    @for ($i = 1; $i <= 5; $i++)
                        <x-shop.icon name="star" class="size-3 {{ $i <= round($rating) ? 'fill-star text-star' : 'text-line' }}" />
                    @endfor
                </span>
                <span class="text-[11px] font-semibold">{{ number_format($rating, 1) }}</span>
                @if ($reviews) <span class="text-[11px] text-muted">({{ $reviews }})</span> @endif
            @endif
        </div>

        <div class="mt-auto pt-3">
            @if ($canAddToCart)
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="{{ $btn }}">
                        <x-shop.icon name="cart" class="size-3.5" /> Add to cart
                    </button>
                </form>
            @else
                <a href="{{ $url }}" class="{{ $btn }}">
                    <x-shop.icon name="cart" class="size-3.5" /> View options
                </a>
            @endif
        </div>
    </div>
</article>
