@extends('layouts.guest')
@php
    use App\Models\Wishlist;
    use App\Support\{ProductPage, Stock};
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Str;

    $wishlistIds = Auth::check()
        ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->all()
        : [];
    $relatedProducts = $products->where('id', '!=', $product->id)->take(8);

    $cart = session('cart', []);
    $colors = $product->colors ?? [];
    $hasColors = ! empty($colors);
    $isInCart = ! $hasColors && collect($cart)->contains(fn ($item) => (int) ($item['product_id'] ?? 0) === $product->id);

    // Same source of truth used by cart/checkout/SeoService.
    $currentPrice = \App\Support\Price::final($product);
    $basePrice = (float) str_replace(',', '', (string) $product->price);
    $onSale = $currentPrice > 0 && $currentPrice < $basePrice;

    $soldOut = Stock::soldOut($product);
    $lowLeft = Stock::lowCount($product);
    $maxQty = Stock::tracked($product) ? max(1, (int) $product->stock_quantity) : 99;

    [$deliverFrom, $deliverTo] = $deliveryWindow;
    $sections = $description['sections'];
    $features = collect($sections)->first(fn ($html, $title) => Str::contains(Str::lower($title), 'feature'));
    $featureItems = $features ? ProductPage::listItems($features) : [];
    $ageSection = collect($sections)->first(fn ($html, $title) => Str::contains(Str::lower($title), ['age', 'size']));
    $otherSections = collect($sections)->reject(fn ($html, $title) => Str::contains(Str::lower($title), ['feature', 'age', 'size']));

    $wa = ltrim(config('shop.contact.whatsapp'), '+');
@endphp

@section('content')
<div class="container-shop space-y-10 pb-28 pt-4 md:space-y-14 md:pb-12">
    {{-- Breadcrumbs --}}
    <nav aria-label="Breadcrumb" class="text-xs text-muted sm:text-sm">
        <ol class="flex flex-wrap items-center gap-1.5">
            <li><a href="{{ route('index') }}" class="hover:text-ink">Home</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="{{ route('shop') }}" class="hover:text-ink">Shop</a></li>
            @if ($product->category)
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-ink">{{ $product->category->name }}</a></li>
            @endif
        </ol>
    </nav>

    <section aria-label="Product details" class="grid grid-cols-1 items-start gap-5 md:grid-cols-2 lg:gap-10">
        {{-- Gallery: sticky on desktop while the details column scrolls --}}
        <div class="md:sticky md:top-24">
            <x-shop.product-gallery :product="$product" />
        </div>

        <div class="min-w-0 space-y-4">
            {{-- Title, rating, price --}}
            <div class="space-y-2">
                @if ($product->category)
                    <a href="{{ route('shop.category', $product->category->slug) }}" class="text-xs font-extrabold uppercase tracking-wider text-brand-600">{{ $product->category->name }}</a>
                @endif
                <h1 class="font-display text-2xl font-semibold leading-tight text-ink sm:text-3xl">{{ $product->product_title }}</h1>
                @if ($reviewData['total'])
                    <a href="#reviews" class="inline-flex items-center gap-2 text-sm">
                        <x-rating-stars :rating="$reviewData['average']" :count="$reviewData['total']" size="sm" :show-value="true" />
                    </a>
                @endif
                @if ($product->sku)
                    <p class="text-xs text-muted">SKU: {{ $product->sku }}</p>
                @endif
            </div>

            <div class="rounded-card bg-white p-4 shadow-soft">
                <x-price :amount="$currentPrice" :original="$onSale ? $basePrice : null" size="lg" />
                <p class="mt-1 text-xs text-muted">Price includes all taxes · Delivery charges shown at checkout</p>
                @if ($soldOut)
                    <p class="mt-2 text-sm font-extrabold text-coral-700">Sold out — message us on WhatsApp to be told when it's back.</p>
                @elseif ($lowLeft)
                    <p class="mt-2 flex items-center gap-1.5 text-sm font-extrabold text-coral-600"><span class="size-2 animate-pulse rounded-full bg-coral-600" aria-hidden="true"></span> Only {{ $lowLeft }} left in stock</p>
                @endif
            </div>

            {{-- Purchase form --}}
            <form id="product-form" action="{{ route('cart.store') }}" method="POST" class="space-y-5"
                  data-price="{{ $currentPrice }}"
                  data-sku="{{ $product->sku ?: $product->id }}"
                  data-name="{{ $product->product_title }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                @if ($hasColors)
                    <x-shop.color-picker :colors="$colors" />
                @endif

                <div class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="quantityInput" class="mb-2 block text-sm font-bold text-ink">Quantity</label>
                        <div class="inline-flex items-center overflow-hidden rounded-full border border-line bg-white">
                            <button type="button" id="minusBtn" aria-label="Decrease quantity" class="grid size-12 place-items-center text-lg text-ink transition hover:bg-coral-50 active:scale-95">&minus;</button>
                            <input type="number" name="quantity" id="quantityInput" value="1" min="1" max="{{ $maxQty }}" step="1" required inputmode="numeric"
                                   class="h-12 w-14 border-0 bg-transparent p-0 text-center font-bold text-ink focus:outline-none focus:ring-0">
                            <button type="button" id="plusBtn" aria-label="Increase quantity" class="grid size-12 place-items-center text-lg text-ink transition hover:bg-coral-50 active:scale-95">&plus;</button>
                        </div>
                    </div>
                    @error('quantity') <p class="w-full text-xs font-bold text-coral-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap" data-main-cta>
                    @if ($soldOut)
                        <x-button variant="soft" type="button" :disabled="true" class="flex-1">Sold out</x-button>
                    @elseif ($isInCart)
                        <x-button :href="route('checkout')" icon="zap" class="flex-1">Go to Checkout</x-button>
                        <x-button variant="soft" type="button" :disabled="true" icon="check" class="flex-1">Already in Cart</x-button>
                    @else
                        {{-- Primary first; name/value tell CartController which one was pressed. --}}
                        <x-button name="buy_now" value="1" icon="zap" size="lg" class="flex-1">Buy Now — Cash on Delivery</x-button>
                        <x-button name="buy_now" value="0" variant="secondary" icon="bag" size="lg" class="flex-1">Add to Cart</x-button>
                    @endif
                </div>

                {{-- Order on WhatsApp: message is filled in with the chosen colour and quantity --}}
                <a href="https://wa.me/{{ $wa }}" data-wa-order target="_blank" rel="noopener"
                   data-wa-base="https://wa.me/{{ $wa }}?text="
                   data-product="{{ $product->product_title }}" data-url="{{ route('productDetails', $product->slug) }}"
                   class="btn btn-whatsapp w-full">
                    <x-shop.icon name="whatsapp" class="size-5" /> Order on WhatsApp
                </a>
            </form>

            {{-- Delivery estimate (1–3 working days, Mon–Sat, Pakistan time) --}}
            <div class="flex items-start gap-3 rounded-card bg-mint-100 p-4">
                <x-shop.icon name="truck" class="mt-0.5 size-5 shrink-0 text-mint-600" />
                <p class="text-sm text-ink">
                    Order now, get it between <strong>{{ $deliverFrom->format('D, j M') }}</strong> and <strong>{{ $deliverTo->format('D, j M') }}</strong>
                    <span class="block text-xs text-muted">Delivery to every city in Pakistan · 1–3 working days</span>
                </p>
            </div>

            {{-- Trust row --}}
            <ul class="grid grid-cols-2 gap-2 text-xs font-bold text-ink sm:grid-cols-4">
                @foreach ([['banknote', 'Cash on Delivery'], ['package', 'Open parcel first'], ['rotate-ccw', '14-day returns'], ['lock', 'Secure checkout']] as [$icon, $label])
                    <li class="flex flex-col items-center gap-1.5 rounded-2xl bg-white p-3 text-center shadow-soft">
                        <x-shop.icon :name="$icon" class="size-5 text-coral-600" /> {{ $label }}
                    </li>
                @endforeach
            </ul>

            {{-- Details as accordions --}}
            <div class="divide-y divide-line overflow-hidden rounded-card border border-line bg-white">
                <details class="group" open>
                    <summary class="flex min-h-14 cursor-pointer list-none items-center justify-between px-4 font-display text-lg font-semibold [&::-webkit-details-marker]:hidden">
                        Description <x-shop.icon name="chevron-down" class="size-5 text-muted transition-transform group-open:rotate-180" />
                    </summary>
                    <div class="product-description px-4 pb-4 text-sm leading-7 text-muted">
                        {!! $description['intro'] ?: '' !!}
                        @foreach ($otherSections as $title => $html)
                            <h3 class="mt-4 font-display text-base font-semibold text-ink">{{ $title }}</h3>
                            {!! $html !!}
                        @endforeach
                    </div>
                </details>

                @if ($features)
                    <details class="group">
                        <summary class="flex min-h-14 cursor-pointer list-none items-center justify-between px-4 font-display text-lg font-semibold [&::-webkit-details-marker]:hidden">
                            Key Features <x-shop.icon name="chevron-down" class="size-5 text-muted transition-transform group-open:rotate-180" />
                        </summary>
                        <div class="px-4 pb-4 text-sm text-muted">
                            @if ($featureItems)
                                <ul class="space-y-2.5">
                                    @foreach ($featureItems as $item)
                                        <li class="flex gap-2.5"><span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-mint-100 text-mint-600"><x-shop.icon name="check" class="size-3.5" /></span> <span class="leading-6">{{ $item }}</span></li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="product-description leading-7">{!! $features !!}</div>
                            @endif
                        </div>
                    </details>
                @endif

                @if ($ageSection || $product->suggested_min_age_months !== null || $product->size)
                    <details class="group">
                        <summary class="flex min-h-14 cursor-pointer list-none items-center justify-between px-4 font-display text-lg font-semibold [&::-webkit-details-marker]:hidden">
                            Size &amp; Age Guide <x-shop.icon name="chevron-down" class="size-5 text-muted transition-transform group-open:rotate-180" />
                        </summary>
                        <div class="product-description px-4 pb-4 text-sm leading-7 text-muted">
                            @if ($product->size) <p><strong class="text-ink">Size:</strong> {{ $product->size }}</p> @endif
                            @if ($product->suggested_min_age_months !== null || $product->suggested_max_age_months !== null)
                                <p><strong class="text-ink">Suitable for:</strong>
                                    {{ $product->suggested_min_age_months ?? 0 }}{{ $product->suggested_max_age_months ? '–' . $product->suggested_max_age_months : '+' }} months</p>
                            @endif
                            {!! $ageSection ?? '' !!}
                        </div>
                    </details>
                @endif

                <details class="group">
                    <summary class="flex min-h-14 cursor-pointer list-none items-center justify-between px-4 font-display text-lg font-semibold [&::-webkit-details-marker]:hidden">
                        Delivery &amp; Returns <x-shop.icon name="chevron-down" class="size-5 text-muted transition-transform group-open:rotate-180" />
                    </summary>
                    <div class="space-y-2 px-4 pb-4 text-sm leading-7 text-muted">
                        <p>{{ config('shop.delivery.message') }}</p>
                        <p>Cash on Delivery is available for orders under Rs. 40,000 — open the parcel and check your order before you pay.</p>
                        <p>Return or exchange within 14 days if the item is unused and in its original packaging. <a href="{{ route('return-refund-policy') }}" class="font-bold text-brand-600 underline">Return policy</a> · <a href="{{ route('shipping-policy') }}" class="font-bold text-brand-600 underline">Shipping policy</a></p>
                    </div>
                </details>
            </div>
        </div>
    </section>

    {{-- Frequently bought together: real co-purchases only --}}
    @if ($boughtTogether->isNotEmpty())
        <section aria-labelledby="fbt-title" class="space-y-4">
            <x-section-header id="fbt-title" title="Frequently Bought Together" subtitle="Other moms ordered these with it." />
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($boughtTogether as $p)
                    <x-product-card :product="$p" :wishlisted="in_array($p->id, $wishlistIds)" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Reviews --}}
    <section id="reviews" aria-labelledby="reviews-title" class="scroll-mt-24 space-y-5">
        <x-section-header id="reviews-title" title="Customer Reviews" />
        <div class="grid gap-6 lg:grid-cols-[20rem_1fr]">
            <div class="card space-y-4 p-5">
                @if ($reviewData['total'])
                    <div class="flex items-end gap-3">
                        <span class="font-display text-5xl font-semibold text-ink">{{ number_format($reviewData['average'], 1) }}</span>
                        <div class="pb-1">
                            <x-rating-stars :rating="$reviewData['average']" size="md" />
                            <p class="text-xs text-muted">{{ $reviewData['total'] }} {{ Str::plural('review', $reviewData['total']) }}</p>
                        </div>
                    </div>
                    <ul class="space-y-1.5">
                        @foreach ($reviewData['breakdown'] as $stars => $n)
                            @php $pct = $reviewData['total'] ? round($n / $reviewData['total'] * 100) : 0; @endphp
                            <li class="flex items-center gap-2 text-xs">
                                <span class="w-6 font-bold text-ink">{{ $stars }}★</span>
                                <span class="h-2 flex-1 overflow-hidden rounded-full bg-mist-100" role="img" aria-label="{{ $n }} {{ Str::plural('review', $n) }} with {{ $stars }} stars">
                                    <span class="block h-full rounded-full bg-star" style="width: {{ $pct }}%"></span>
                                </span>
                                <span class="w-6 text-right text-muted">{{ $n }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-muted">No reviews yet. Bought this? Your review helps other moms decide.</p>
                @endif
                <button type="button" data-sheet-open="review-sheet" class="btn btn-secondary w-full">Write a review</button>
                @if (session('review_submitted'))
                    <p class="rounded-xl bg-mint-100 p-3 text-xs font-bold text-mint-600" role="status">Thank you! Your review will appear after a quick check.</p>
                @endif
            </div>

            <div class="space-y-3">
                @forelse ($reviewData['items'] as $review)
                    <article class="card p-5">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-rating-stars :rating="$review->rating" size="sm" />
                            @if ($review->is_verified)
                                <x-badge tone="success" size="xs"><x-shop.icon name="check" class="size-3" /> Verified buyer</x-badge>
                            @endif
                        </div>
                        <p class="mt-2 text-sm leading-7 text-ink">{{ $review->body }}</p>
                        @if ($review->photo_url)
                            <a href="{{ $review->photo_url }}" target="_blank" rel="noopener" class="mt-3 inline-block">
                                <img src="{{ \App\Support\WebpImage::url($review->photo) ?? $review->photo_url }}" alt="Photo from {{ $review->display_name }}" width="112" height="112" loading="lazy" class="size-28 rounded-img object-cover">
                            </a>
                        @endif
                        <p class="mt-3 text-xs font-bold text-muted">{{ $review->display_name }}{{ $review->city ? ' · ' . $review->city : '' }} · {{ $review->created_at?->format('M Y') }}</p>
                    </article>
                @empty
                @endforelse
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section aria-labelledby="pfaq-title" class="space-y-4">
        <x-section-header id="pfaq-title" title="Frequently Asked Questions" />
        <div class="space-y-2">
            @foreach ([
                'Is Cash on Delivery available for this product?' => 'Yes, Cash on Delivery is available for orders under Rs. 40,000.',
                'What is the return policy for this product?' => 'You can request a return or exchange within 14 days of delivery, provided the item is unused and in its original packaging.',
                'How long will delivery take?' => config('shop.delivery.message'),
            ] as $question => $answer)
                <details class="group rounded-2xl border border-line bg-white p-4">
                    <summary class="flex min-h-8 cursor-pointer list-none items-center justify-between gap-3 text-sm font-bold text-ink [&::-webkit-details-marker]:hidden">
                        {{ $question }}
                        <x-shop.icon name="chevron-down" class="size-4 shrink-0 text-muted transition group-open:rotate-180" />
                    </summary>
                    <p class="mt-2 text-sm leading-6 text-muted">{{ $answer }}</p>
                </details>
            @endforeach
        </div>
    </section>

    {{-- You may also like: swipe row on phones, grid on desktop --}}
    @if ($relatedProducts->isNotEmpty())
        <section aria-labelledby="related-title" class="space-y-4">
            <x-section-header id="related-title" title="You May Also Like" :heart="true" link-text="View all" :href="$product->category ? route('shop.category', $product->category->slug) : route('shop')" />
            <div class="-mx-4 flex snap-x snap-mandatory scroll-px-4 gap-3 overflow-x-auto px-4 pb-2 [scrollbar-width:none] sm:mx-0 sm:grid sm:grid-cols-3 sm:overflow-visible sm:px-0 lg:grid-cols-4 [&::-webkit-scrollbar]:hidden">
                @foreach ($relatedProducts as $relatedProduct)
                    <x-product-card :product="$relatedProduct" :wishlisted="in_array($relatedProduct->id, $wishlistIds)"
                                    class="w-[46%] shrink-0 snap-start sm:w-auto" />
                @endforeach
            </div>
        </section>
    @endif
</div>

{{-- Sticky buy bar (phones), shown once the main buttons scroll out of view --}}
@unless ($soldOut || $isInCart)
    <div id="sticky-buy" aria-hidden="true"
         class="fixed inset-x-0 bottom-0 z-50 translate-y-full border-t border-line bg-white/95 px-4 pb-[max(.75rem,env(safe-area-inset-bottom))] pt-3 shadow-lift backdrop-blur transition-transform duration-300 ease-soft md:hidden">
        <div class="flex items-center gap-3">
            <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-bold text-muted">{{ $product->product_title }}</p>
                <p class="font-extrabold text-coral-600">{{ money($currentPrice) }}</p>
            </div>
            <button type="submit" form="product-form" name="buy_now" value="1" class="btn btn-primary shrink-0 px-5" tabindex="-1">
                <x-shop.icon name="zap" class="size-5" /> Buy Now
            </button>
        </div>
    </div>
@endunless

{{-- Write a review --}}
<x-bottom-sheet id="review-sheet" title="Write a review" side="center">
    <form action="{{ route('reviews.store', $product->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <fieldset>
            <legend class="mb-2 text-sm font-bold text-ink">Your rating</legend>
            {{-- DOM order 5→1, shown 1→5: ":checked ~ label" lights the chosen star and every lower one. --}}
            <div class="rating-picker flex flex-row-reverse justify-end gap-1">
                @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" name="rating" id="rate-{{ $i }}" value="{{ $i }}" class="rating-input sr-only" required @checked(old('rating') == $i)>
                    <label for="rate-{{ $i }}" class="rating-star cursor-pointer text-line" title="{{ $i }} stars">
                        <x-shop.icon name="star" class="size-9 fill-current" /><span class="sr-only">{{ $i }} stars</span>
                    </label>
                @endfor
            </div>
        </fieldset>
        <div class="grid gap-3 sm:grid-cols-2">
            <label class="block text-sm font-bold text-ink">Your name
                <input name="name" required maxlength="60" value="{{ old('name', Auth::user()->first_name ?? '') }}" class="mt-1 block min-h-11 w-full rounded-xl border border-line px-3 font-normal">
            </label>
            <label class="block text-sm font-bold text-ink">City
                <input name="city" maxlength="60" value="{{ old('city') }}" class="mt-1 block min-h-11 w-full rounded-xl border border-line px-3 font-normal">
            </label>
        </div>
        <label class="block text-sm font-bold text-ink">Your review
            <textarea name="body" required minlength="10" maxlength="1500" rows="4" class="mt-1 block w-full rounded-xl border border-line p-3 font-normal">{{ old('body') }}</textarea>
        </label>
        <label class="block text-sm font-bold text-ink">Phone number you ordered with <span class="font-normal text-muted">(optional — adds a "Verified buyer" badge; never shown)</span>
            <input name="phone" inputmode="tel" maxlength="20" placeholder="03XX-XXXXXXX" class="mt-1 block min-h-11 w-full rounded-xl border border-line px-3 font-normal">
        </label>
        <label class="block text-sm font-bold text-ink">Add a photo <span class="font-normal text-muted">(optional)</span>
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full text-sm font-normal">
        </label>
        <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
        @if ($errors->hasAny(['rating', 'name', 'body', 'photo', 'website']))
            <p class="rounded-xl bg-coral-50 p-3 text-xs font-bold text-coral-700">{{ $errors->first() }}</p>
        @endif
        <x-button class="w-full sm:w-full">Submit review</x-button>
        <p class="text-center text-xs text-muted">Reviews appear after a quick check.</p>
    </form>
</x-bottom-sheet>
@endsection

@push('style')
<style>
    .product-description { overflow-wrap: anywhere; }
    .product-description img { max-width: 100%; height: auto; border-radius: 1rem; }
    .product-description ul { padding-left: 1.25rem; list-style: disc; }
    .product-description ol { padding-left: 1.25rem; list-style: decimal; }
    .product-description p + p { margin-top: .75rem; }
    .product-description h3 { color: var(--color-ink); font-weight: 600; margin-top: 1rem; }
    .rating-picker .rating-input:checked ~ .rating-star,
    .rating-picker .rating-star:hover,
    .rating-picker .rating-star:hover ~ .rating-star { color: var(--color-star); }
    .rating-picker .rating-input:focus-visible + .rating-star { outline: 2px solid var(--color-brand-600); outline-offset: 2px; border-radius: 6px; }
</style>
@endpush

@push('script')
<script>
    (() => {
        const q = document.getElementById('quantityInput');
        const plus = document.getElementById('plusBtn'), minus = document.getElementById('minusBtn');
        const max = parseInt(q?.max, 10) || 99;
        if (q && plus && minus) {
            const clamp = (v) => Math.min(max, Math.max(1, parseInt(v, 10) || 1));
            plus.addEventListener('click', () => { q.value = clamp(+q.value + 1); });
            minus.addEventListener('click', () => { q.value = clamp(+q.value - 1); });
            q.addEventListener('change', () => { q.value = clamp(q.value); });
        }

        // WhatsApp order: product, chosen colour, quantity and link in the message.
        const wa = document.querySelector('[data-wa-order]');
        if (wa) {
            wa.addEventListener('click', () => {
                const color = document.querySelector('#product-form input[name="color"]:checked');
                const lines = [
                    'Assalam o Alaikum! I want to order:',
                    '• ' + wa.dataset.product,
                    color ? '• Colour: ' + color.dataset.colorName : null,
                    '• Quantity: ' + (q ? q.value : 1),
                    wa.dataset.url,
                ].filter(Boolean);
                wa.href = wa.dataset.waBase + encodeURIComponent(lines.join('\n'));
                if (window.fbqTrack) fbqTrack('Contact', { content_name: wa.dataset.product });
            });
        }

        // Sticky buy bar: visible only while the main buttons are off-screen.
        const bar = document.getElementById('sticky-buy');
        const cta = document.querySelector('[data-main-cta]');
        if (bar && cta && 'IntersectionObserver' in window) {
            new IntersectionObserver(([entry]) => {
                // Whenever the main buttons are not on screen (still below on load, or scrolled past).
                const show = !entry.isIntersecting;
                bar.classList.toggle('translate-y-full', !show);
                bar.setAttribute('aria-hidden', show ? 'false' : 'true');
                bar.querySelector('button').tabIndex = show ? 0 : -1;
            }).observe(cta);
        }

        // Re-open the review sheet when the server sent back validation errors.
        @if ($errors->hasAny(['rating', 'name', 'body', 'photo', 'website']))
            window.addEventListener('load', () => window.sheet && window.sheet.open('review-sheet'));
        @endif
    })();
</script>
@endpush

@push('script')
<script>
    // TikTok ViewContent: one real event per product page load.
    ttqTrack('ViewContent', {
        contents: [{
            content_id: @json((string) $product->id),
            content_type: 'product',
            content_name: @json($product->product_title),
            quantity: 1,
            price: {{ (float) $currentPrice }},
        }],
        value: {{ (float) $currentPrice }},
        currency: 'PKR',
    });

    // Meta Pixel ViewContent: one real event per product page load.
    fbqTrack('ViewContent', {
        content_ids: [@js((string) $product->id)],
        content_type: 'product',
        content_name: @js($product->product_title),
        contents: [{ id: @js((string) $product->id), quantity: 1 }],
        value: {{ (float) $currentPrice }},
        currency: 'PKR',
    }, @js($viewEventId ?? null));

    // GA4 view_item
    gaEvent('view_item', {
        value: {{ (float) $currentPrice }},
        items: [{ item_id: @js((string) $product->id), item_name: @js($product->product_title), item_category: @js($product->category->name ?? ''), price: {{ (float) $currentPrice }}, quantity: 1 }],
    });
</script>
@endpush
