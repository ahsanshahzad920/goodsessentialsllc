@extends('layouts.guest')

@php
    use App\Models\Wishlist;
    use Illuminate\Support\Str;

    // One query for the whole page (the old view ran one query per product).
    $wishlistIds = Auth::check()
        ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->all()
        : [];

    $social = array_filter(config('shop.social', []));
    $giftLink = $giftProducts->count() === 1
        ? route('productDetails', $giftProducts->first()->slug)
        : route('shop', ['q' => 'gift']);
@endphp

@section('content')
<div class="container-shop space-y-12 pb-12 pt-4 md:space-y-16 md:pt-6">

    {{-- 1. Hero (first slide headline = page h1) --}}
    <x-shop.hero-slider :slides="$heroSlides" />

    {{-- 2. Trust strip, right under the hero --}}
    <x-trust-strip class="-mt-8 md:-mt-10" />

    {{-- 3. Shop by category --}}
    <x-shop.category-bubbles :categories="$bubbles" />

    {{-- 4. Deals of the week (+ real countdown when the admin set an end time) --}}
    @if ($deals['products']->isNotEmpty())
        <section data-reveal aria-labelledby="deals-title" class="space-y-4 rounded-card bg-coral-50 p-4 sm:p-6" data-countdown-scope>
            <div class="space-y-2">
                <x-section-header id="deals-title" eyebrow="Limited-time prices" title="Deals of the Week" link-text="All deals" :href="route('shop', ['sort' => 'price_asc'])" />
                <x-countdown :end="$deals['ends_at']" label="Ends in" />
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4" data-reveal-stagger>
                @foreach ($deals['products'] as $product)
                    <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistIds)" :sold="$deals['sold'][$product->id] ?? null" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- 5a. Best sellers (only once enough products have real sales) --}}
    @if ($bestSellers->isNotEmpty())
        <section data-reveal aria-labelledby="best-title" class="space-y-4">
            <x-section-header id="best-title" eyebrow="Loved by moms" title="Best Sellers" :heart="true" link-text="View all" :href="route('shop')" />
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4" data-reveal-stagger>
                @foreach ($bestSellers as $product)
                    <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistIds)" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- 5b. New arrivals --}}
    @if ($newArrivals->isNotEmpty())
        <section data-reveal aria-labelledby="new-title" class="space-y-4">
            <x-section-header id="new-title" eyebrow="Just in" title="New Arrivals" link-text="View all products" :href="route('shop')" />
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4" data-reveal-stagger>
                @foreach ($newArrivals as $product)
                    <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistIds)" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- 6. Shop by age (only buckets that contain tagged products) --}}
    @if ($ageBuckets)
        <section data-reveal aria-labelledby="age-title" class="space-y-4">
            <x-section-header id="age-title" title="Shop by Age" subtitle="Find what fits your little one right now." />
            <ul class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach ($ageBuckets as $key => [$label, $count])
                    @php $tone = ['lavender', 'mint', 'sky', 'blush'][$loop->index % 4]; @endphp
                    <li>
                        <a href="{{ route('shop', ['age' => $key]) }}"
                           class="flex min-h-20 flex-col justify-center rounded-card bg-{{ $tone }}-100 px-4 py-3 transition-transform duration-200 ease-soft hover:-translate-y-0.5 active:scale-[.97]">
                            <span class="font-display text-lg font-semibold text-{{ $tone }}-600">{{ $label }}</span>
                            <span class="text-xs font-bold text-muted">{{ $count }} {{ Str::plural('product', $count) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- 7. Gift box / Aqiqa --}}
    @if ($giftProducts->isNotEmpty())
        @php $gift = $giftProducts->first(); @endphp
        <section data-reveal aria-labelledby="gift-title" class="relative overflow-hidden rounded-card bg-butter/60">
            <span class="pointer-events-none absolute -left-10 -top-10 size-48 rounded-full bg-blush/70" aria-hidden="true"></span>
            <span class="pointer-events-none absolute -bottom-16 right-10 size-56 rounded-full bg-white/60" aria-hidden="true"></span>
            <div class="relative grid items-center gap-6 p-6 md:grid-cols-2 md:p-10">
                <div>
                    {{-- Gift box whose lid pops open (CSS keyframes; static with reduced motion) --}}
                    <svg viewBox="0 0 64 64" class="mb-3 size-16" aria-hidden="true">
                        <rect x="10" y="30" width="44" height="28" rx="5" fill="#f26b5b"/>
                        <rect x="29" y="30" width="6" height="28" fill="#ffe7a3"/>
                        <g class="animate-lid [transform-box:fill-box] [transform-origin:0%_100%]">
                            <rect x="6" y="20" width="52" height="12" rx="4" fill="#b3372a"/>
                            <rect x="29" y="20" width="6" height="12" fill="#ffe7a3"/>
                            <path d="M32 20c-4-9-14-10-14-4s8 4 14 4zm0 0c4-9 14-10 14-4s-8 4-14 4z" fill="#ffe7a3" stroke="#d99a0b" stroke-width="1.5"/>
                        </g>
                    </svg>
                    <p class="text-xs font-extrabold uppercase tracking-wider text-sun-600">Newborn gifts · Aqiqa</p>
                    <h2 id="gift-title" class="mt-1 font-display text-3xl font-semibold leading-tight text-ink md:text-4xl">A thoughtful gift for the new baby</h2>
                    <p class="mt-3 max-w-md text-sm leading-7 text-ink/75">Ready-to-give sets, delivered to their door anywhere in Pakistan — with Cash on Delivery.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <x-button :href="route('gift-box')" icon="gift">Build a Gift Box</x-button>
                        <x-button :href="$giftLink" variant="secondary" :block="false">Ready-made sets</x-button>
                    </div>
                </div>
                <a href="{{ route('productDetails', $gift->slug) }}" class="mx-auto block" tabindex="-1" aria-hidden="true">
                    <x-picture :path="$gift->product_image" sizes="(min-width: 768px) 288px, 224px" width="400" height="400"
                               class="h-56 w-auto -rotate-3 rounded-card bg-white object-contain p-3 shadow-lift md:h-72" />
                </a>
            </div>
        </section>
    @endif

    {{-- 8. Customer love: real reviews --}}
    <x-shop.testimonials :reviews="$testimonials" :empty-state="true" data-reveal />

    @if ($social)
        <section aria-label="Follow us" class="flex flex-wrap items-center justify-center gap-3 rounded-card bg-white p-5 text-center shadow-soft">
            <p class="w-full font-display text-lg font-semibold text-ink sm:w-auto">See real orders &amp; unboxings</p>
            @foreach ($social as $network => $url)
                <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-soft btn-sm">{{ Str::title($network) }}</a>
            @endforeach
        </section>
    @endif

    {{-- 9. Why moms trust us --}}
    <section data-reveal aria-labelledby="trust-title" class="space-y-5">
        <x-section-header id="trust-title" align="center" eyebrow="Shop with confidence" title="Why Moms Trust Us" />
        <ul class="grid gap-4 md:grid-cols-3">
            @foreach ([
                ['shield', 'mint', 'Checked before dispatch', 'Every order is checked and packed with care before it leaves our Bahawalpur store.'],
                ['package', 'blush', 'Open the parcel first', 'Pay on delivery after you have opened the parcel and seen your order — no risk.'],
                ['whatsapp', 'lavender', 'Real people on WhatsApp', 'Questions about sizes or delivery? Message us and a real person replies.'],
            ] as [$icon, $tone, $title, $text])
                <li class="card flex gap-4 p-5">
                    <span class="grid size-14 shrink-0 place-items-center rounded-2xl bg-{{ $tone }}-100 text-{{ $tone }}-600">
                        <x-shop.icon :name="$icon" class="size-7" />
                    </span>
                    <span>
                        <span class="block font-display text-lg font-semibold text-ink">{{ $title }}</span>
                        <span class="mt-1 block text-sm leading-6 text-muted">{{ $text }}</span>
                    </span>
                </li>
            @endforeach
        </ul>
    </section>

    {{-- 10. FAQ (same source as the FAQPage schema: App\Support\StoreFaq) --}}
    <section data-reveal aria-labelledby="faq-title" class="space-y-4">
        <x-section-header id="faq-title" align="center" title="Frequently Asked Questions" />
        <div class="mx-auto max-w-3xl space-y-2">
            @foreach ($homeFaqs as $question => $answer)
                <details class="group rounded-2xl border border-line bg-white p-4 open:shadow-soft">
                    <summary class="flex min-h-8 cursor-pointer list-none items-center justify-between gap-3 text-sm font-bold text-ink [&::-webkit-details-marker]:hidden">
                        {{ $question }}
                        <x-shop.icon name="chevron-down" class="size-4 shrink-0 text-muted transition-transform group-open:rotate-180" />
                    </summary>
                    <p class="mt-2 text-sm leading-6 text-muted">{{ $answer }}</p>
                </details>
            @endforeach
        </div>
    </section>

    {{-- 11. About / SEO copy, collapsed above the footer --}}
    <section data-reveal aria-labelledby="about-store-title" class="mx-auto max-w-3xl rounded-card bg-white/60 p-5 text-center">
        <h2 id="about-store-title" class="font-display text-lg font-semibold text-ink">Baby &amp; Mom Essentials in Pakistan</h2>
        <details class="group mt-2">
            <summary class="mx-auto inline-flex min-h-11 cursor-pointer list-none items-center gap-1 text-sm font-extrabold text-brand-600 [&::-webkit-details-marker]:hidden">
                <span class="group-open:hidden">Read more</span><span class="hidden group-open:inline">Show less</span>
                <x-shop.icon name="chevron-down" class="size-4 transition-transform group-open:rotate-180" />
            </summary>
            <p class="mt-2 text-sm leading-7 text-muted">
                Goods Essentials is an online store for baby and mom essentials in Pakistan, offering wholesale-rate
                pricing on good quality products. We sell baby clothing, feeding supplies, baby care and grooming
                products, toys, and newborn gift sets, sourced for safety and everyday quality.
                {{ config('shop.delivery.message') }} Plus a 14-day return window — so you
                can shop for your little one with confidence.
            </p>
        </details>
    </section>
</div>
@endsection
