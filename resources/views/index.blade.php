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
        <h2 class="text-2xl font-bold text-ink">Frequently Asked Questions</h2>
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
    @if ($testimonials->isNotEmpty())
        @php $tones = ['blush', 'lilac', 'sun']; @endphp
        <section aria-label="Customer testimonials" class="space-y-6">
            <div class="text-center">
                <span class="text-sm font-bold uppercase tracking-wider text-blush-600">
                    Kind Words from Customers
                </span>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-ink">
                    What Our Customers Say
                </h2>
                <p class="mt-3 text-sm text-muted">
                    Feedback shared by customers about their shopping experiences.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach ($testimonials as $review)
                    @php $tone = $tones[$loop->index % count($tones)]; @endphp
                    <article class="flex flex-col rounded-3xl border border-line bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center gap-1 text-sun-100" role="img" aria-label="{{ $review->rating }} out of 5 stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star text-sm {{ $i <= $review->rating ? '' : 'opacity-30' }}"></i>
                            @endfor
                        </div>

                        <i class="fa-solid fa-quote-left mt-5 text-2xl text-{{ $tone }}-100"></i>

                        <p class="mt-3 flex-1 text-sm leading-7 text-muted">{{ $review->body }}</p>

                        <div class="mt-6 flex items-center gap-3 border-t border-line pt-5">
                            @if ($review->photo_url)
                                <img src="{{ $review->photo_url }}" alt="" width="44" height="44" loading="lazy" class="size-11 rounded-full object-cover">
                            @else
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-{{ $tone }}-100 font-bold text-{{ $tone }}-600" aria-hidden="true">
                                    {{ $review->initials }}
                                </div>
                            @endif
                            <div>
                                <h3 class="text-sm font-bold text-ink">{{ $review->display_name }}</h3>
                                @if ($review->city)
                                    <p class="mt-1 text-xs text-muted">{{ $review->city }}</p>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <x-shop.trust-bar />
</div>
@endsection
