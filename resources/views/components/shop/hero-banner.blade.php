{{-- Single static hero banner (the carousel was removed per request — only
     one promo remains, so dots/arrows/auto-rotate had nothing left to do).
     This is the homepage LCP element: WebP first, explicit size (no CLS),
     eager + high fetch priority. --}}
<section class="kids-hero relative isolate overflow-hidden rounded-[2rem]">
    <div class="kids-hero-scallop kids-hero-scallop-top"></div>
    <div class="kids-hero-scallop kids-hero-scallop-bottom"></div>

    <a href="{{ route('productDetails', 'sw-6-character-baby-wrapping-swaddle') }}" class="block">
        <picture>
            <source type="image/webp" srcset="{{ asset('assets/img/hero-sw6-penguin-banner-v2.webp') }}">
            <img
                src="{{ asset('assets/img/hero-sw6-penguin-banner-v2.png') }}"
                alt="Character Baby Wrapping Swaddle Bed Set — Flat 35% off, Rs. 5,850"
                width="1920" height="600"
                class="h-auto w-full"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            >
        </picture>
    </a>
</section>
