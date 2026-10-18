{{-- Auto-rotating hero carousel — slides every 2 seconds (Bootstrap 5) --}}
<section class="kids-hero relative isolate overflow-hidden rounded-[2rem]">
    <div class="kids-hero-scallop kids-hero-scallop-top"></div>
    <div class="kids-hero-scallop kids-hero-scallop-bottom"></div>

    <div
        id="heroBannerCarousel"
        class="carousel slide"
        data-bs-ride="carousel"
        data-bs-interval="2000"
    >
        {{-- ── Dot indicators ── --}}
        <div class="carousel-indicators" style="bottom: 0.75rem;">
            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="0"
                class="active rounded-full" aria-current="true" aria-label="Slide 1"
                style="width:2rem;height:0.5rem;border-radius:9999px;background:#f472b6;border:none;opacity:1;"></button>
            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="1"
                aria-label="Slide 2"
                style="width:0.625rem;height:0.5rem;border-radius:9999px;background:#fbcfe8;border:none;opacity:1;"></button>
            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="2"
                aria-label="Slide 3"
                style="width:0.625rem;height:0.5rem;border-radius:9999px;background:#fbcfe8;border:none;opacity:1;"></button>
        </div>

        {{-- ── Slides ── --}}
        <div class="carousel-inner">

            {{-- Slide 1 — Baby & Mom Essentials --}}
            <div class="carousel-item active">
                <div class="grid items-center gap-10 px-6 py-12 sm:px-10 lg:grid-cols-2 lg:gap-12 lg:px-14 lg:py-14"
                     style="background:linear-gradient(120deg,#fff0f3 0%,#fce7f3 100%);">
                    <div class="order-2 space-y-5 text-center lg:order-1 lg:text-left">
                        <div class="flex items-center justify-center gap-2 text-base font-semibold text-slate-800 lg:justify-start">
                            <span>Everything for your</span>
                            <svg class="h-6 w-6 text-pink-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/>
                            </svg>
                        </div>
                        <h1 class="sr-only">Baby &amp; Mom Essentials, Delivered Across Pakistan</h1>
                        <p class="font-display text-5xl font-extrabold leading-tight tracking-tight text-indigo-950 sm:text-6xl xl:text-7xl">
                            Little Ones'
                            <span class="block text-pink-500">Big Moments.</span>
                        </p>
                        <p class="text-base leading-7 text-slate-600 sm:text-lg">
                            Baby clothing, feeding, care, toys &amp; newborn gifts — sourced for safety and everyday quality.
                        </p>
                        <div class="flex flex-wrap justify-center gap-3 lg:justify-start">
                            <a href="{{ route('shop') }}"
                               class="inline-flex items-center gap-2 rounded-full bg-pink-500 px-7 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-pink-600">
                                Shop Now
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                            <a href="{{ route('shop') }}"
                               class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-7 py-3.5 text-sm font-bold text-slate-700 transition hover:border-pink-400 hover:text-pink-600">
                                View All Categories
                            </a>
                        </div>
                    </div>
                    <div class="order-1 flex justify-center lg:order-2">
                        <div class="flex h-56 w-56 items-center justify-center rounded-3xl bg-pink-100 shadow-lg sm:h-72 sm:w-72">
                            <span style="font-size:5rem;">👶</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 2 — Cash on Delivery --}}
            <div class="carousel-item">
                <div class="grid items-center gap-10 px-6 py-12 sm:px-10 lg:grid-cols-2 lg:gap-12 lg:px-14 lg:py-14"
                     style="background:linear-gradient(120deg,#f0fdf4 0%,#dcfce7 100%);">
                    <div class="order-2 space-y-5 text-center lg:order-1 lg:text-left">
                        <div class="flex items-center justify-center gap-2 text-base font-semibold text-slate-800 lg:justify-start">
                            <span>Hassle-free shopping</span>
                            <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm4.3 7.61-4.57 6a1 1 0 0 1-.79.39 1 1 0 0 1-.79-.38l-2.44-3.2a1 1 0 0 1 1.58-1.23l1.63 2.14 3.78-5a1 1 0 1 1 1.6 1.28z"/>
                            </svg>
                        </div>
                        <p class="font-display text-5xl font-extrabold leading-tight tracking-tight text-indigo-950 sm:text-6xl xl:text-7xl">
                            Cash on
                            <span class="block text-green-500">Delivery.</span>
                        </p>
                        <p class="text-base leading-7 text-slate-600 sm:text-lg">
                            Order with confidence — COD available across Pakistan. Karachi in 1–2 days, nationwide in 4–5 days.
                        </p>
                        <div class="flex flex-wrap justify-center gap-3 lg:justify-start">
                            <a href="{{ route('shop') }}"
                               class="inline-flex items-center gap-2 rounded-full bg-green-500 px-7 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-green-600">
                                Order Now
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="order-1 flex justify-center lg:order-2">
                        <div class="flex h-56 w-56 items-center justify-center rounded-3xl bg-green-100 shadow-lg sm:h-72 sm:w-72">
                            <span style="font-size:5rem;">🚚</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 3 — 14-Day Returns --}}
            <div class="carousel-item">
                <div class="grid items-center gap-10 px-6 py-12 sm:px-10 lg:grid-cols-2 lg:gap-12 lg:px-14 lg:py-14"
                     style="background:linear-gradient(120deg,#faf5ff 0%,#ede9fe 100%);">
                    <div class="order-2 space-y-5 text-center lg:order-1 lg:text-left">
                        <div class="flex items-center justify-center gap-2 text-base font-semibold text-slate-800 lg:justify-start">
                            <span>Shop worry-free</span>
                            <svg class="h-6 w-6 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                        </div>
                        <p class="font-display text-5xl font-extrabold leading-tight tracking-tight text-indigo-950 sm:text-6xl xl:text-7xl">
                            14-Day Easy
                            <span class="block text-purple-500">Returns.</span>
                        </p>
                        <p class="text-base leading-7 text-slate-600 sm:text-lg">
                            Not happy? Return or exchange within 14 days. Unused item in original packaging — we make it simple.
                        </p>
                        <div class="flex flex-wrap justify-center gap-3 lg:justify-start">
                            <a href="{{ route('shop') }}"
                               class="inline-flex items-center gap-2 rounded-full bg-purple-500 px-7 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-purple-600">
                                Start Shopping
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="order-1 flex justify-center lg:order-2">
                        <div class="flex h-56 w-56 items-center justify-center rounded-3xl bg-purple-100 shadow-lg sm:h-72 sm:w-72">
                            <span style="font-size:5rem;">🛡️</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /.carousel-inner --}}

        {{-- ── Prev / Next arrows ── --}}
        <button class="carousel-control-prev" type="button"
                data-bs-target="#heroBannerCarousel" data-bs-slide="prev"
                style="width:2.5rem;left:0.5rem;">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/80 shadow-md">
                <svg class="h-4 w-4 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button"
                data-bs-target="#heroBannerCarousel" data-bs-slide="next"
                style="width:2.5rem;right:0.5rem;">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/80 shadow-md">
                <svg class="h-4 w-4 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </span>
            <span class="visually-hidden">Next</span>
        </button>

    </div>{{-- /.carousel --}}
</section>
