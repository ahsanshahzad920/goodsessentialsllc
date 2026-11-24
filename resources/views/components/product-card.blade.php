{{--
    The one product card for every grid (homepage, shop, related products).
    <x-product-card :product="$p" :wishlisted="true" :priority="$loop->index < 2" :sold="12" />

    Query with Product::withCardData() so the gallery and review stats load
    without extra queries.

    Hooks other code relies on (keep them):
      - form[action=cart.store] + data-price / data-sku / data-name -> AddToCart pixel (guest layout)
      - form[data-ajax-cart]                                       -> fly-to-cart + toast (x-shop.card-script)
      - .js-wishlist + data-product-id / data-url / data-login     -> wishlist toggle
      - [data-card-gallery] / [data-card-dot]                      -> swipe dots, hover swap
--}}
@props(['product', 'wishlisted' => false, 'priority' => false, 'sold' => null, 'sizes' => null])

@php
    use App\Support\Stock;

    $price = (float) str_replace(',', '', (string) ($product->price ?? 0));
    $final = (float) str_replace(',', '', (string) (calculateDiscountPrice($product->price, $product->dis_price) ?? $price));
    $onSale = $final > 0 && $final < $price;
    $pay = $final ?: $price;
    $url = route('productDetails', $product->slug);

    // Up to 3 gallery images (cover first); falls back to the single cover image.
    $paths = $product->relationLoaded('images') && $product->images->isNotEmpty()
        ? $product->images->pluck('path')->take(3)->all()
        : array_filter([$product->product_image]);
    $gallery = array_map(fn ($p) => ['path' => $p], $paths) ?: [['path' => null]];
    // Card width: 2 columns on phones, 3 on tablets, ~4 (max 280px) on desktop;
    // carousels (homepage rails) are about the same width.
    $sizes = $sizes ?? '(min-width: 1280px) 280px, (min-width: 1024px) 23vw, (min-width: 640px) 31vw, 46vw';

    $rating  = $product->reviews_count ? $product->reviews_avg_rating : null;
    $reviews = $product->reviews_count ?: null;

    $soldOut = Stock::soldOut($product);
    $lowLeft = Stock::lowCount($product);

    // Two or more colours need a choice on the product page; a single colour is
    // sent automatically (CartController requires one of the product's own colours).
    $colors = $product->colors ?? [];
    $canAddToCart = ! $soldOut && \Illuminate\Support\Facades\Route::has('cart.store') && count($colors) <= 1;
    $onlyColor = count($colors) === 1 ? $colors[0]['hex'] : null;
@endphp

<article {{ $attributes->class('tilt group relative flex min-w-0 flex-col overflow-hidden rounded-card border border-line bg-white duration-300 ease-soft hover:shadow-lift') }}>
    <div class="relative bg-frame">
        {{-- Gallery: swipe on touch screens, second image on hover with a mouse --}}
        <div class="flex snap-x snap-mandatory overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
             @if (count($gallery) > 1) data-card-gallery @endif>
            @foreach ($gallery as $i => $img)
                <a href="{{ $url }}" class="block w-full shrink-0 snap-center" tabindex="-1" aria-hidden="true">
                    <x-picture :path="$img['path']" :sizes="$sizes" :priority="$priority && $i === 0"
                               :alt="$i === 0 ? $product->product_title : ''"
                               class="aspect-square w-full object-contain p-3 mix-blend-multiply {{ $soldOut ? 'opacity-50 grayscale' : '' }}" />
                </a>
            @endforeach
        </div>

        @if (count($gallery) > 1)
            <div class="pointer-events-none absolute inset-x-0 bottom-2 flex justify-center gap-1" aria-hidden="true">
                @foreach ($gallery as $i => $img)
                    <span data-card-dot class="h-1.5 rounded-full transition-[width,background-color] duration-300 {{ $i === 0 ? 'w-4 bg-ink/60' : 'w-1.5 bg-ink/20' }}"></span>
                @endforeach
            </div>
        @endif

        <div class="pointer-events-none absolute left-2.5 top-2.5 flex flex-col items-start gap-1">
            @if ($soldOut)
                <x-badge tone="neutral">Sold out</x-badge>
            @elseif ($onSale)
                <x-badge tone="sale">-{{ round((1 - $final / $price) * 100) }}%</x-badge>
            @elseif ($product->created_at?->gt(now()->subDays(14)))
                <x-badge tone="new">New</x-badge>
            @endif
        </div>

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

        <x-price :amount="$pay" :original="$onSale ? $price : null" size="sm" />

        @if ($lowLeft)
            {{-- Real inventory (products.stock_quantity), never a made-up number. --}}
            <p class="flex items-center gap-1 text-xs font-extrabold text-coral-600">
                <span class="size-1.5 animate-pulse rounded-full bg-coral-600" aria-hidden="true"></span> Only {{ $lowLeft }} left
            </p>
        @endif

        @if ($sold)
            {{-- Real units ordered in the last 7 days (App\Support\HomePage::soldThisWeek). --}}
            <x-badge tone="warning" size="xs" class="self-start">{{ $sold }} sold this week</x-badge>
        @endif

        <div class="relative z-10 mt-auto pt-2">
            @if ($soldOut)
                <x-button variant="soft" size="sm" type="button" :disabled="true" class="w-full px-2 sm:w-full">Sold out</x-button>
            @elseif ($canAddToCart)
                <form method="POST" action="{{ route('cart.store') }}" data-ajax-cart
                      data-price="{{ $pay }}"
                      data-sku="{{ $product->sku ?: $product->id }}"
                      data-name="{{ $product->product_title }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    @if ($onlyColor) <input type="hidden" name="color" value="{{ $onlyColor }}"> @endif
                    <x-button variant="soft" size="sm" icon="cart" class="w-full px-2 text-center leading-tight whitespace-normal sm:w-full max-[400px]:[&>svg]:hidden">Add to cart</x-button>
                </form>
            @else
                <x-button :href="$url" variant="soft" size="sm" icon="cart" class="w-full px-2 text-center leading-tight whitespace-normal sm:w-full max-[400px]:[&>svg]:hidden">Choose options</x-button>
            @endif
        </div>
    </div>
</article>
