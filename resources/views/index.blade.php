@extends('layouts.guest')

@php
    use App\Models\Wishlist;

    // One query for the whole page (the old view ran one query per product).
    $wishlistIds = Auth::check()
        ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->all()
        : [];

    // Only categories with at least one product — see Category::navItems().
    $categories = \App\Models\Category::navItems();

    // Promo cards reference categories by name in config/shop.php; resolve
    // to a real slug when one exists, otherwise fall back to the full shop page.
    $promoHref = function (?string $categoryName) use ($categories) {
        if (! $categoryName) {
            return route('shop');
        }
        $match = $categories->firstWhere(fn ($c) => strcasecmp($c['name'], $categoryName) === 0);
        return $match ? route('shop.category', $match['slug']) : route('shop');
    };

    $homeFaqs = [
        'Does Goods Essentials offer Cash on Delivery?' => 'Yes. Cash on Delivery is available across Pakistan for orders under Rs. 40,000. Orders above that amount require a partial advance payment by bank transfer.',
        'How long does delivery take?' => config('shop.delivery.message') . ' Delivery may take longer during major sales.',
        'What is the delivery charge?' => 'Prepaid orders have a Rs. 200 delivery charge, and Cash on Delivery orders have a Rs. 250 delivery charge. Charges are shown at checkout before you place your order.',
        'Can I return or exchange a product?' => 'Yes. Contact us within 14 days of delivery to request a return or exchange. The item must be unused, in its original packaging, with tags and accessories intact.',
        'What happens if my order arrives damaged?' => 'Contact us as soon as possible and within 14 days of delivery. We may ask for photographs or an unboxing video, and we cover return shipping for verified damaged or incorrect items.',
        'Do you deliver outside Karachi?' => 'Yes, we deliver to every city in Pakistan, not just Karachi — ' . lcfirst(config('shop.delivery.message')),
    ];
@endphp

@section('content')
<div class="container-shop space-y-10 py-8">

    {{-- Hero banner --}}
    <x-shop.hero-banner />

    {{-- Intro --}}
    <section aria-label="About Goods Essentials" class="mx-auto max-w-3xl text-center">
        <p class="text-sm leading-7 text-muted sm:text-base">
            Goods Essentials is an online store for baby and mom essentials in Pakistan, offering wholesale-rate
            pricing on good quality products. We sell baby clothing, feeding supplies, baby care and grooming
            products, toys, and newborn gift sets, sourced for safety and everyday quality.
            {{ config('shop.delivery.message') }} Plus a 14-day return window — so you
            can shop for your little one with confidence.
        </p>
    </section>

    {{-- Category circles (from config/shop.php) --}}
    <section aria-label="Shop by category" class="grid grid-cols-[repeat(auto-fit,minmax(5.5rem,1fr))] gap-y-6">
        @foreach ($categories as $c)
            <x-shop.category-circle :label="$c['label'] ?? $c['name']" :icon="$c['icon']" :tone="$c['tone']" :href="route('shop.category', $c['slug'])" />
        @endforeach
    </section>

    {{-- Promo cards (from config/shop.php) --}}
    <section aria-label="Featured collections" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach (config('shop.promos', []) as $p)
            <x-shop.promo-card :title="$p['title']" :text="$p['text']" :tone="$p['tone']"
                               :image="asset($p['image'])"
                               :href="$promoHref($p['category'] ?? null)" />
        @endforeach
    </section>

    {{-- Popular picks: $babyProducts --}}
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

    {{-- Deals of the week: $latestProducts --}}
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

    {{-- Homepage FAQ (matches the FAQPage schema in IndexController) --}}
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

    {{-- Testimonials --}}
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

            <article class="flex flex-col rounded-3xl border border-line bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-1 text-sun-100" aria-label="5 out of 5 stars">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fa-solid fa-star text-sm"></i>
                    @endfor
                </div>

                <i class="fa-solid fa-quote-left mt-5 text-2xl text-blush-100"></i>

                <p class="mt-3 flex-1 text-sm leading-7 text-muted">
                    I received my suit today, and I'm so happy with the fabric quality. The stitching is excellent and professional, and the delivery was fast. I'll be buying my suits for future events from Goods Essentials.
                </p>

                <div class="mt-6 flex items-center gap-3 border-t border-line pt-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blush-100 font-bold text-blush-600">
                        NC
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-ink">Sarah</h3>
                        <p class="mt-1 text-xs text-muted">Pakpatan</p>
                    </div>
                </div>
            </article>

            <article class="flex flex-col rounded-3xl border border-line bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-1 text-sun-100" aria-label="5 out of 5 stars">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fa-solid fa-star text-sm"></i>
                    @endfor
                </div>

                <i class="fa-solid fa-quote-left mt-5 text-2xl text-lilac-100"></i>

                <p class="mt-3 flex-1 text-sm leading-7 text-muted">
                    I had a wonderful experience with GoodsEssentials. Their customer service was awesome and patient while I added and removed items from my order. They made sure everything was perfect.
                </p>

                <div class="mt-6 flex items-center gap-3 border-t border-line pt-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-lilac-100 font-bold text-lilac-600">
                        RS
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-ink">Ayesha Khan</h3>
                        <p class="mt-1 text-xs text-muted">Gujranwala</p>
                    </div>
                </div>
            </article>

            <article class="flex flex-col rounded-3xl border border-line bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-1 text-sun-100" aria-label="5 out of 5 stars">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fa-solid fa-star text-sm"></i>
                    @endfor
                </div>

                <i class="fa-solid fa-quote-left mt-5 text-2xl text-sun-100"></i>

                <p class="mt-3 flex-1 text-sm leading-7 text-muted">
                    Mera shopping experience bht bht axha rha. Products ky rates bht munasib hn, must buy thanks.
                </p>

                <div class="mt-6 flex items-center gap-3 border-t border-line pt-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-sun-100 font-bold text-sun-600">
                        RP
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-ink">Guest-amir665</h3>
                        <p class="mt-1 text-xs text-muted">Pattoki</p>
                    </div>
                </div>
            </article>

        </div>
    </section>

    <x-shop.trust-bar />
</div>
@endsection
