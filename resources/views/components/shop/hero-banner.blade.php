{{-- Auto-rotating hero carousel — slides every 3 seconds. Dependency-free
     (no Bootstrap JS/CSS exists in this Tailwind project; the earlier
     data-bs-* markup was inert without it). --}}
<section class="kids-hero relative isolate overflow-hidden rounded-[2rem]">
    <div class="kids-hero-scallop kids-hero-scallop-top"></div>
    <div class="kids-hero-scallop kids-hero-scallop-bottom"></div>

    <div id="heroCarousel" class="hero-carousel relative" role="region" aria-roledescription="carousel" aria-label="Featured promotions">
        <div class="hero-carousel-track">
            {{-- Slide 1 — Baby & Mom Essentials --}}
            <div class="hero-slide is-active" role="group" aria-roledescription="slide" aria-label="1 of 4">
                <div class="grid items-center gap-10 px-6 py-12 sm:px-10 lg:grid-cols-2 lg:gap-12 lg:px-14 lg:py-14"
                     style="background:linear-gradient(120deg,#fff0f3 0%,#fce7f3 100%);">
                    <div class="order-2 space-y-5 text-center lg:order-1 lg:text-left">
                        <div class="flex items-center justify-center gap-2 text-base font-semibold text-ink lg:justify-start">
                            <span>Everything for your</span>
                            <svg class="h-6 w-6 text-blush-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/>
                            </svg>
                        </div>
                        <h1 class="sr-only">Baby &amp; Mom Essentials, Delivered Across Pakistan</h1>
                        <p class="font-display text-5xl font-extrabold leading-tight tracking-tight text-ink sm:text-6xl xl:text-7xl">
                            Little Ones'
                            <span class="block text-blush-600">Big Moments.</span>
                        </p>
                        <p class="text-base leading-7 text-muted sm:text-lg">
                            Baby clothing, feeding, care, toys &amp; newborn gifts — at wholesale rates, without compromising on quality.
                        </p>
                        <div class="flex flex-wrap justify-center gap-3 lg:justify-start">
                            <a href="{{ route('shop') }}"
                               class="inline-flex items-center gap-2 rounded-full bg-blush-600 px-7 py-3.5 text-sm font-bold text-white shadow-sm transition hover:brightness-90">
                                Shop Now
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                            <a href="{{ route('shop') }}"
                               class="inline-flex items-center gap-2 rounded-full border border-blush-100 bg-white px-7 py-3.5 text-sm font-bold text-ink transition hover:border-blush-600 hover:text-blush-600">
                                View All Categories
                            </a>
                        </div>
                    </div>
                    <div class="order-1 flex justify-center lg:order-2">
                        <div class="flex h-56 w-56 items-center justify-center rounded-3xl bg-blush-100 text-blush-600 shadow-lg sm:h-72 sm:w-72">
                            <x-shop.icon name="stroller" class="size-24 sm:size-32" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 2 — Cash on Delivery --}}
            <div class="hero-slide" role="group" aria-roledescription="slide" aria-label="2 of 4">
                <div class="grid items-center gap-10 px-6 py-12 sm:px-10 lg:grid-cols-2 lg:gap-12 lg:px-14 lg:py-14"
                     style="background:linear-gradient(120deg,#f0fdf4 0%,#dcfce7 100%);">
                    <div class="order-2 space-y-5 text-center lg:order-1 lg:text-left">
                        <div class="flex items-center justify-center gap-2 text-base font-semibold text-ink lg:justify-start">
                            <span>Hassle-free shopping</span>
                            <svg class="h-6 w-6 text-mint-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm4.3 7.61-4.57 6a1 1 0 0 1-.79.39 1 1 0 0 1-.79-.38l-2.44-3.2a1 1 0 0 1 1.58-1.23l1.63 2.14 3.78-5a1 1 0 1 1 1.6 1.28z"/>
                            </svg>
                        </div>
                        <p class="font-display text-5xl font-extrabold leading-tight tracking-tight text-ink sm:text-6xl xl:text-7xl">
                            Cash on
                            <span class="block text-mint-600">Delivery.</span>
                        </p>
                        <p class="text-base leading-7 text-muted sm:text-lg">
                            Order with confidence — {{ config('shop.delivery.message') }}
                        </p>
                        <div class="flex flex-wrap justify-center gap-3 lg:justify-start">
                            <a href="{{ route('shop') }}"
                               class="inline-flex items-center gap-2 rounded-full bg-mint-600 px-7 py-3.5 text-sm font-bold text-white shadow-sm transition hover:brightness-90">
                                Order Now
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="order-1 flex justify-center lg:order-2">
                        <div class="flex h-56 w-56 items-center justify-center rounded-3xl bg-mint-100 text-mint-600 shadow-lg sm:h-72 sm:w-72">
                            <x-shop.icon name="truck" class="size-24 sm:size-32" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 3 — 14-Day Returns --}}
            <div class="hero-slide" role="group" aria-roledescription="slide" aria-label="3 of 4">
                <div class="grid items-center gap-10 px-6 py-12 sm:px-10 lg:grid-cols-2 lg:gap-12 lg:px-14 lg:py-14"
                     style="background:linear-gradient(120deg,#faf5ff 0%,#ede9fe 100%);">
                    <div class="order-2 space-y-5 text-center lg:order-1 lg:text-left">
                        <div class="flex items-center justify-center gap-2 text-base font-semibold text-ink lg:justify-start">
                            <span>Shop worry-free</span>
                            <svg class="h-6 w-6 text-lilac-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                        </div>
                        <p class="font-display text-5xl font-extrabold leading-tight tracking-tight text-ink sm:text-6xl xl:text-7xl">
                            14-Day Easy
                            <span class="block text-lilac-600">Returns.</span>
                        </p>
                        <p class="text-base leading-7 text-muted sm:text-lg">
                            Not happy? Return or exchange within 14 days. Unused item in original packaging — we make it simple.
                        </p>
                        <div class="flex flex-wrap justify-center gap-3 lg:justify-start">
                            <a href="{{ route('shop') }}"
                               class="inline-flex items-center gap-2 rounded-full bg-lilac-600 px-7 py-3.5 text-sm font-bold text-white shadow-sm transition hover:brightness-90">
                                Start Shopping
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="order-1 flex justify-center lg:order-2">
                        <div class="flex h-56 w-56 items-center justify-center rounded-3xl bg-lilac-100 text-lilac-600 shadow-lg sm:h-72 sm:w-72">
                            <x-shop.icon name="shield" class="size-24 sm:size-32" />
                        </div>
                    </div>
                </div>
            </div>
            {{-- Slide 4 — SW-6 Character Swaddle promo (image, links to the product) --}}
            <div class="hero-slide" role="group" aria-roledescription="slide" aria-label="4 of 4">
                <a href="{{ route('productDetails', 'sw-6-character-baby-wrapping-swaddle') }}" class="block">
                    <img
                        src="{{ asset('assets/img/hero-sw6-penguin-banner.png') }}"
                        alt="SW-6 Character Baby Wrapping Swaddle Bed Set — Flat 40% off, Rs. 5,400"
                        class="h-auto w-full"
                        loading="lazy"
                    >
                </a>
            </div>
        </div>{{-- /.hero-carousel-track --}}

        {{-- Dot indicators --}}
        <div class="hero-carousel-dots" role="tablist" aria-label="Choose slide">
            <button type="button" class="hero-dot is-active" data-slide-to="0" role="tab" aria-selected="true" aria-label="Slide 1"></button>
            <button type="button" class="hero-dot" data-slide-to="1" role="tab" aria-selected="false" aria-label="Slide 2"></button>
            <button type="button" class="hero-dot" data-slide-to="2" role="tab" aria-selected="false" aria-label="Slide 3"></button>
            <button type="button" class="hero-dot" data-slide-to="3" role="tab" aria-selected="false" aria-label="Slide 4"></button>
        </div>

        {{-- Prev / Next arrows --}}
        <button type="button" class="hero-carousel-arrow hero-carousel-prev" data-slide-dir="-1" aria-label="Previous slide">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/80 shadow-md">
                <svg class="h-4 w-4 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </span>
        </button>
        <button type="button" class="hero-carousel-arrow hero-carousel-next" data-slide-dir="1" aria-label="Next slide">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/80 shadow-md">
                <svg class="h-4 w-4 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </span>
        </button>
    </div>{{-- /.hero-carousel --}}
</section>

@push('style')
<style>
    .hero-carousel-track { position: relative; min-height: 1px; }
    .hero-slide {
        display: none;
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .hero-slide.is-active {
        display: block;
        opacity: 1;
    }
    .hero-carousel-dots {
        position: absolute;
        left: 50%;
        bottom: 0.9rem;
        transform: translateX(-50%);
        display: flex;
        gap: 0.4rem;
        z-index: 5;
    }
    .hero-dot {
        width: 0.625rem;
        height: 0.5rem;
        border-radius: 9999px;
        background: var(--color-brand-100);
        border: none;
        padding: 0;
        cursor: pointer;
        transition: width 0.2s ease, background-color 0.2s ease;
    }
    .hero-dot.is-active {
        width: 2rem;
        background: var(--color-brand-500);
    }
    .hero-carousel-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        background: transparent;
        border: none;
        padding: 0;
        cursor: pointer;
    }
    .hero-carousel-prev { left: 0.5rem; }
    .hero-carousel-next { right: 0.5rem; }
    @media (prefers-reduced-motion: reduce) {
        .hero-slide { transition: none; }
    }
</style>
@endpush

@push('script')
<script>
    (function () {
        var root = document.getElementById('heroCarousel');
        if (!root) return;

        var slides = Array.prototype.slice.call(root.querySelectorAll('.hero-slide'));
        var dots = Array.prototype.slice.call(root.querySelectorAll('.hero-dot'));
        var current = 0;
        var intervalMs = 3000;
        var timer = null;
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function show(index) {
            current = (index + slides.length) % slides.length;
            slides.forEach(function (slide, i) {
                slide.classList.toggle('is-active', i === current);
            });
            dots.forEach(function (dot, i) {
                var active = i === current;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-selected', active ? 'true' : 'false');
            });
        }

        function next() { show(current + 1); }

        function start() {
            if (reduceMotion || slides.length < 2) return;
            stop();
            timer = window.setInterval(next, intervalMs);
        }

        function stop() {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                show(parseInt(dot.dataset.slideTo, 10));
                start();
            });
        });

        root.querySelectorAll('[data-slide-dir]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                show(current + parseInt(btn.dataset.slideDir, 10));
                start();
            });
        });

        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', start);
        root.addEventListener('focusin', stop);
        root.addEventListener('focusout', start);

        show(0);
        start();
    })();
</script>
@endpush
