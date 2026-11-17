@extends('layouts.guest')

@php
    use App\Models\Wishlist;

    // One query for the whole page (the old view ran one query per product).
    $wishlistIds = Auth::check()
        ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->all()
        : [];

    // Only categories with at least one product — see Category::navItems().
    $categories = \App\Models\Category::navItems();

    // $homeTiles, $testimonials and $homeFaqs come from IndexController.
@endphp

@section('content')
<div class="container-shop space-y-10 py-8">

    {{-- Hero banner --}}
    <x-shop.hero-banner />

    {{-- Intro (holds the page's single <h1>; the hero is an image banner) --}}
    <section aria-labelledby="home-title" class="mx-auto max-w-3xl text-center">
        <h1 id="home-title" class="text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
            Baby &amp; Mom Essentials in Pakistan
        </h1>
        <p class="mt-3 text-sm leading-7 text-muted sm:text-base">
            Goods Essentials is an online store for baby and mom essentials in Pakistan, offering wholesale-rate
            pricing on good quality products. We sell baby clothing, feeding supplies, baby care and grooming
            products, toys, and newborn gift sets, sourced for safety and everyday quality.
            {{ config('shop.delivery.message') }} Plus a 14-day return window — so you
            can shop for your little one with confidence.
        </p>
    </section>

    {{-- Category circles --}}
    <section aria-label="Shop by category" class="grid grid-cols-[repeat(auto-fit,minmax(5.5rem,1fr))] gap-y-6">
        @foreach ($categories as $c)
            <x-shop.category-circle :label="$c['label'] ?? $c['name']" :icon="$c['icon']" :tone="$c['tone']" :href="route('shop.category', $c['slug'])" />
        @endforeach
    </section>

    {{-- Collection tiles: real categories with real product photos (Category::homeTiles) --}}
    @if ($homeTiles->isNotEmpty())
        <section aria-label="Featured collections" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($homeTiles as $t)
                <x-shop.promo-card :title="$t['label']"
                                   :text="$t['text'] ?? ($t['count'] . ' ' . \Illuminate\Support\Str::plural('product', $t['count']))"
                                   :tone="$t['tone']"
                                   :image="$t['image']"
                                   :icon="$t['icon']"
                                   :href="route('shop.category', $t['slug'])" />
            @endforeach
        </section>
    @endif

    {{-- Popular picks --}}
    <section aria-label="Popular picks" class="space-y-4">
        <x-shop.section-heading title="Popular Picks" :heart="true" link-text="View All Products" :href="route('shop')" />
        @if ($babyProducts->isNotEmpty())
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($babyProducts->take(6) as $product)
                    <x-shop.product-card :product="$product" :wishlisted="in_array($product->id, $wishlistIds)" />
                @endforeach
            </div>
        @else
            <p class="rounded-2xl bg-white p-6 text-center text-sm text-muted">No products found yet.</p>
        @endif
    </section>

    {{-- Deals of the week --}}
    @if ($latestProducts->isNotEmpty())
        <section aria-label="Deals of the week" class="space-y-4">
            <x-shop.section-heading title="Deals of the Week" link-text="Shop All Deals" :href="route('shop')" />
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($latestProducts->take(6) as $product)
                    <x-shop.product-card :product="$product" :wishlisted="in_array($product->id, $wishlistIds)" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Homepage FAQ (same source as the FAQPage schema: App\Support\StoreFaq) --}}
    <section aria-label="Frequently asked questions" class="space-y-4">
        <x-section-header title="Frequently Asked Questions" />
        <div class="mx-auto max-w-3xl space-y-2">
            @foreach ($homeFaqs as $question => $answer)
                <details class="group rounded-2xl border border-line bg-white p-4">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-sm font-semibold text-ink">
                        {{ $question }}
                        <x-shop.icon name="arrow-right" class="size-4 shrink-0 text-muted transition group-open:rotate-90" />
                    </summary>
                    <p class="mt-2 text-sm leading-6 text-muted">{{ $answer }}</p>
                </details>
            @endforeach
        </div>
    </section>

    {{-- Testimonials: approved + featured rows from the reviews table --}}
    <x-shop.testimonials :reviews="$testimonials" />

    <x-shop.trust-bar />
</div>
@endsection
