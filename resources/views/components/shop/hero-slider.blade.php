{{--
    Homepage hero: up to 3 product slides (App\Support\HomePage::heroSlides()).
    Native scroll-snap = free swipe on phones; JS only adds dots, arrows and
    autoplay (paused on hover/focus/touch/hidden tab, off for reduced motion).
    Mobile: image above text, sized so the CTA is visible on a 360×640 screen.
    The first slide's headline is the page <h1>.
--}}
@props(['slides'])

@php
    $tones = [
        ['bg' => 'bg-lavender-100', 'blob' => 'bg-brand-100', 'blob2' => 'bg-butter', 'eyebrow' => 'text-lavender-600'],
        ['bg' => 'bg-sky-100',      'blob' => 'bg-white',     'blob2' => 'bg-mint',   'eyebrow' => 'text-sky-600'],
        ['bg' => 'bg-blush-100',    'blob' => 'bg-blush',     'blob2' => 'bg-butter', 'eyebrow' => 'text-blush-600'],
    ];
    $ctaText = \App\Support\Settings::get('hero_cta_text', 'Order Now — Cash on Delivery');
    $ticks = ['Cash on Delivery', 'Open parcel before paying', '1–3 day delivery'];
@endphp

@if ($slides->isNotEmpty())
<section class="hero relative" aria-roledescription="carousel" aria-label="Featured products" data-hero>
    <ul class="flex snap-x snap-mandatory overflow-x-auto rounded-card [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" data-hero-track>
        @foreach ($slides as $i => $s)
            @php $t = $tones[$i % count($tones)]; $first = $i === 0; @endphp
            <li class="relative w-full shrink-0 snap-center overflow-hidden {{ $t['bg'] }}"
                role="group" aria-roledescription="slide" aria-label="{{ $i + 1 }} of {{ $slides->count() }}" data-hero-slide>
                {{-- Soft decorative shapes (pure CSS; the 3D layer arrives in Phase 7) --}}
                <span class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full {{ $t['blob'] }} opacity-70 md:size-96" aria-hidden="true"></span>
                <span class="pointer-events-none absolute -bottom-20 left-1/3 size-48 rounded-full {{ $t['blob2'] }} opacity-50" aria-hidden="true"></span>

                <div class="relative grid items-center gap-2 px-4 pb-4 pt-3 md:grid-cols-[1.05fr_1fr] md:gap-8 md:px-12 md:py-10 lg:px-16">
                    {{-- Image: first on mobile, right on desktop --}}
                    <a href="{{ $s['url'] }}" class="relative mx-auto block md:order-2" tabindex="-1" aria-hidden="true">
                        <span class="absolute inset-[8%] rounded-full bg-white/70 blur-2xl" aria-hidden="true"></span>
                        <picture>
                            @if ($s['webp']) <source type="image/webp" srcset="{{ $s['webp'] }}"> @endif
                            <img src="{{ $s['image'] }}" alt="{{ $s['product']->product_title }}"
                                 width="600" height="600" decoding="async"
                                 @if ($first) loading="eager" fetchpriority="high" @else loading="lazy" @endif
                                 class="relative h-[min(24vh,10.5rem)] w-auto object-contain mix-blend-multiply drop-shadow-xl md:h-[22rem]">
                        </picture>
                        @if ($s['badge'])
                            <span class="absolute right-0 top-2 grid size-14 rotate-12 place-items-center rounded-full bg-coral-600 text-center text-[10px] font-extrabold leading-tight text-white shadow-soft md:size-20 md:text-sm">{{ $s['badge'] }}</span>
                        @endif
                    </a>

                    <div class="md:order-1">
                        @if ($s['eyebrow'])
                            <p class="text-xs font-extrabold uppercase tracking-wider {{ $t['eyebrow'] }}">{{ $s['eyebrow'] }}</p>
                        @endif
                        @if ($first)
                            <h1 class="mt-1 line-clamp-3 font-display text-[1.4rem] font-semibold leading-tight text-ink md:text-[2.6rem] lg:text-5xl">{{ $s['headline'] }}</h1>
                        @else
                            <p class="mt-1 line-clamp-3 font-display text-[1.4rem] font-semibold leading-tight text-ink md:text-[2.6rem] lg:text-5xl">{{ $s['headline'] }}</p>
                        @endif

                        <x-price :amount="$s['price']" :original="$s['was']" size="lg" class="mt-1 md:mt-4" />

                        <div class="mt-2 flex flex-col gap-2 sm:flex-row sm:items-center md:mt-6">
                            @if ($s['quick_buy'])
                                <form method="POST" action="{{ route('cart.store') }}"
                                      data-price="{{ $s['price'] }}"
                                      data-sku="{{ $s['product']->sku ?: $s['product']->id }}"
                                      data-name="{{ $s['product']->product_title }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $s['product']->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    @if ($s['color']) <input type="hidden" name="color" value="{{ $s['color'] }}"> @endif
                                    <x-button name="buy_now" value="1" icon="zap" class="w-full sm:w-auto md:min-h-14 md:px-8 md:text-base">{{ $ctaText }}</x-button>
                                </form>
                            @else
                                <x-button :href="$s['url']" icon="zap" class="w-full sm:w-auto md:min-h-14 md:px-8 md:text-base">{{ $ctaText }}</x-button>
                            @endif
                            <a href="{{ $s['url'] }}" class="hidden min-h-11 items-center px-3 text-sm font-extrabold text-brand-700 hover:underline sm:inline-flex">View details</a>
                        </div>

                        <ul class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs font-bold text-ink/80 md:mt-5 md:text-sm">
                            @foreach ($ticks as $tick)
                                <li class="flex items-center gap-1"><x-shop.icon name="check" class="size-4 text-success-600" /> {{ $tick }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>

    {{-- Decorative 3D layer (Phase 7). Stays empty unless resources/js/hero3d.js
         decides the device can afford it; the static hero above is the fallback. --}}
    <div data-hero-3d class="pointer-events-none absolute inset-x-0 top-0 h-[38%] overflow-hidden rounded-t-card md:left-[52%] md:h-[calc(100%-3.75rem)] md:rounded-l-none md:rounded-r-card" aria-hidden="true"></div>
    @vite('resources/js/hero3d.js')

    @if ($slides->count() > 1)
        <div class="mt-3 flex items-center justify-center gap-1" data-hero-dots>
            @foreach ($slides as $i => $s)
                <button type="button" data-hero-dot="{{ $i }}" aria-label="Show slide {{ $i + 1 }}"
                        class="group grid size-11 place-items-center" @if ($i === 0) aria-current="true" @endif>
                    <span class="block h-2 w-2 rounded-full bg-ink/20 transition-[width,background-color] duration-300 ease-soft group-aria-[current=true]:w-6 group-aria-[current=true]:bg-coral-600"></span>
                </button>
            @endforeach
        </div>
        <button type="button" data-hero-prev aria-label="Previous slide"
                class="absolute left-3 top-1/2 hidden size-11 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-ink shadow-soft transition hover:bg-white active:scale-95 md:grid">
            <x-shop.icon name="chevron-left" class="size-5" />
        </button>
        <button type="button" data-hero-next aria-label="Next slide"
                class="absolute right-3 top-1/2 hidden size-11 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-ink shadow-soft transition hover:bg-white active:scale-95 md:grid">
            <x-shop.icon name="chevron-right" class="size-5" />
        </button>

        <script>
            (function () {
                var root = document.currentScript.closest('[data-hero]');
                var track = root.querySelector('[data-hero-track]');
                var slides = root.querySelectorAll('[data-hero-slide]');
                var dots = root.querySelectorAll('[data-hero-dot]');
                var n = slides.length, cur = 0, timer = null, paused = false;
                var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                function go(i) {
                    cur = (i + n) % n;
                    track.scrollTo({ left: cur * track.clientWidth, behavior: reduce ? 'auto' : 'smooth' });
                }
                function mark() {
                    var i = Math.round(track.scrollLeft / Math.max(1, track.clientWidth));
                    cur = Math.max(0, Math.min(n - 1, i));
                    dots.forEach(function (d, k) { d.setAttribute('aria-current', k === cur ? 'true' : 'false'); });
                    // Hidden slides must not be reachable by Tab.
                    slides.forEach(function (s, k) { s.inert = k !== cur; });
                }
                function start() { if (!reduce && !timer && !paused) timer = setInterval(function () { go(cur + 1); }, 5000); }
                function stop() { clearInterval(timer); timer = null; }

                var t; track.addEventListener('scroll', function () { clearTimeout(t); t = setTimeout(mark, 80); }, { passive: true });
                dots.forEach(function (d) { d.addEventListener('click', function () { go(+d.dataset.heroDot); }); });
                root.querySelector('[data-hero-prev]').addEventListener('click', function () { go(cur - 1); });
                root.querySelector('[data-hero-next]').addEventListener('click', function () { go(cur + 1); });

                root.addEventListener('mouseenter', function () { paused = true; stop(); });
                root.addEventListener('mouseleave', function () { paused = false; start(); });
                root.addEventListener('focusin', function () { paused = true; stop(); });
                root.addEventListener('touchstart', function () { paused = true; stop(); }, { passive: true });
                document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });
                window.addEventListener('resize', function () { go(cur); });

                mark();
                start();
            })();
        </script>
    @endif
</section>
@endif
