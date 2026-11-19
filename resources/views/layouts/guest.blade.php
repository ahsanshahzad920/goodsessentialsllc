<!DOCTYPE html>
<html lang="en-PK">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! \Artesaos\SEOTools\Facades\SEOMeta::generate() !!}
    {!! \Artesaos\SEOTools\Facades\OpenGraph::generate() !!}
    {!! \Artesaos\SEOTools\Facades\TwitterCard::generate() !!}
    {!! \App\Services\SeoService::renderSchemas() !!}
    <link rel="shortcut icon" href="{{ asset('img/fav.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- display=swap: text renders immediately in the fallback font. css2 serves
         unicode-range subsets, so browsers only fetch the Latin files they need.
         Font Awesome is no longer loaded on the storefront: all icons are inline
         Lucide SVGs via <x-shop.icon> (one consistent set, no extra request). --}}
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">

    @if ($code = config('tracking.google_site_verification'))
        <meta name="google-site-verification" content="{{ $code }}">
    @endif
    @if ($code = config('tracking.bing_site_verification'))
        <meta name="msvalidate.01" content="{{ $code }}">
    @endif

    {{-- Hide a dismissed announcement bar before first paint (no layout shift). --}}
    <script>try { if (sessionStorage.getItem('ann-off')) document.documentElement.classList.add('ann-off'); } catch (e) {}</script>
    {{-- Arm scroll-reveal before first paint (no flicker); only when it can run and motion is welcome. --}}
    <script>if ('IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion: reduce)').matches) document.documentElement.classList.add('js-reveal');</script>

    @vite(['resources/css/app.css'])
    @stack('style')

    {{-- Analytics: each snippet is fully inert until its ID is set in .env (see config/tracking.php). --}}
    @if ($gaId = config('tracking.ga4_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', @json($gaId));
        </script>
    @endif

    @include('partials.meta-pixel')

    @if ($tiktokId = config('tracking.tiktok_pixel_id'))
        <script>
            !function (w, d, t) {
                w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
                ttq.load(@json($tiktokId));
                ttq.page();
            }(window, document, 'ttq');
        </script>
    @endif
</head>
@php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
<body data-auth="{{ Auth::check() ? '1' : '0' }}" class="pb-[calc(4rem+env(safe-area-inset-bottom))] md:pb-0">
    <a href="#main" class="sr-only z-[60] rounded-full bg-ink px-4 py-2 font-bold text-white focus:not-sr-only focus:fixed focus:left-3 focus:top-3">Skip to content</a>

    <x-shop.announcement-bar />

    {{-- Fixed-height sticky slot: the header inside shrinks on scroll without moving the page. --}}
    <div id="site-header" class="sticky top-0 z-40 h-16 md:h-[72px]">
        <x-shop.header :cart-count="$cartCount" />
    </div>
    <x-shop.nav />

    <main id="main">
        {{-- Bug fix: this component existed but was never included anywhere,
             so success/error messages (add-to-cart, contact form, checkout
             errors) were set in session and silently never shown. --}}
        <x-shop.flash />
        @yield('content')
    </main>

    <x-shop.footer />

    <x-shop.menu-sheet />
    <x-shop.search-dialog />
    <x-shop.cart-drawer />
    <x-shop.social-proof />
    <x-shop.bottom-nav :cart-count="$cartCount" />

    <script>
        // TikTok Pixel helpers: safe no-ops when the Pixel isn't configured
        // (so pages never throw just because TIKTOK_PIXEL_ID is unset), and
        // always attach a per-event event_id for future server-side Events
        // API deduplication (see config/tracking.php tiktok_events_api_token).
        window.ttqTrack = function (event, data, eventId) {
            if (typeof ttq === 'undefined') return;
            eventId = eventId || ((window.crypto && crypto.randomUUID)
                ? crypto.randomUUID()
                : (Date.now() + '-' + Math.random().toString(36).slice(2)));
            ttq.track(event, data || {}, { event_id: eventId });
        };
        window.ttqIdentify = function (data) {
            if (typeof ttq === 'undefined' || !data) return;
            ttq.identify(data);
        };

        // Meta Pixel helper: same safe-no-op pattern as ttqTrack above, so
        // event call sites don't need their own typeof fbq guard.
        window.fbqTrack = function (event, data) {
            if (typeof fbq === 'undefined') return;
            fbq('track', event, data || {});
        };

        // Hamburger menu
        (function () {
            var btn = document.getElementById('menu-toggle');
            var menu = document.getElementById('mobile-menu');
            if (!btn || !menu) return;
            btn.addEventListener('click', function () {
                menu.hidden = !menu.hidden;
                btn.setAttribute('aria-expanded', String(!menu.hidden));
            });
        })();

        // Wishlist hearts (same endpoint as before: POST /wishlist/toggle)
        document.addEventListener('click', async function (e) {
            var btn = e.target.closest('.js-wishlist');
            if (!btn) return;
            e.preventDefault();

            if (document.body.dataset.auth !== '1') {
                window.location.href = btn.dataset.login;
                return;
            }

            btn.disabled = true;
            try {
                var res = await fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: btn.dataset.productId })
                });
                var data = await res.json();
                btn.setAttribute('aria-pressed', data.action === 'added' ? 'true' : 'false');
            } catch (err) {
                console.error('Wishlist error:', err);
            } finally {
                btn.disabled = false;
            }
        });

        // add_to_cart (GA4 + TikTok): fires client-side on submit so it works
        // even though "Add to cart" is a normal (non-AJAX) form POST that
        // reloads the page. Also covers any future AJAX add-to-cart form that
        // posts to the same route with the same data-* attributes.
        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form.matches('form[action="{{ route('cart.store') }}"]')) return;

            var productId = form.querySelector('[name="product_id"]')?.value;
            var quantity = Number(form.querySelector('[name="quantity"]')?.value) || 1;
            if (!productId) return;

            if (typeof gtag === 'function') {
                gtag('event', 'add_to_cart', {
                    items: [{ item_id: productId, quantity: quantity }]
                });
            }

            var price = Number(form.dataset.price) || 0;
            ttqTrack('AddToCart', {
                contents: [{
                    content_id: productId,
                    content_type: 'product',
                    content_name: form.dataset.name || '',
                    quantity: quantity,
                    price: price,
                }],
                value: price * quantity,
                currency: 'PKR',
            });

            fbqTrack('AddToCart', {
                content_ids: [productId],
                content_type: 'product',
                content_name: form.dataset.name || '',
                contents: [{ id: productId, quantity: quantity }],
                value: price * quantity,
                currency: 'PKR',
            });
        });
    </script>

    <x-shop.search-script />
    <x-shop.card-script />

    <script>
        // Scroll reveal: fade/slide up 16px; product grids stagger 60ms per card.
        (function () {
            var root = document.documentElement;
            if (!root.classList.contains('js-reveal')) return;
            document.querySelectorAll('[data-reveal-stagger]').forEach(function (grid) {
                Array.prototype.forEach.call(grid.children, function (c, i) {
                    c.setAttribute('data-reveal', '');
                    c.style.setProperty('--reveal-delay', Math.min(i, 6) * 60 + 'ms');
                });
            });
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
                });
            }, { rootMargin: '0px 0px -8% 0px' });
            document.querySelectorAll('[data-reveal]').forEach(function (el) { io.observe(el); });
            // Printing / save-as-PDF: show everything.
            window.addEventListener('beforeprint', function () { document.querySelectorAll('[data-reveal]').forEach(function (el) { el.classList.add('is-in'); }); });
        })();
    </script>

    <script>
        // Header shrink + mobile bottom-nav hide/show. One rAF-throttled
        // passive scroll listener; height changes only inside a fixed-size slot.
        (function () {
            var slot = document.getElementById('site-header');
            var bottom = document.getElementById('bottom-nav');
            var lastY = window.scrollY, ticking = false;

            function update() {
                var y = window.scrollY;
                if (slot) slot.classList.toggle('is-stuck', y > 8);
                if (bottom) {
                    var nearEnd = window.innerHeight + y >= document.documentElement.scrollHeight - 80;
                    if (y > lastY + 4 && y > 120 && !nearEnd) bottom.classList.add('translate-y-full');
                    else if (y < lastY - 4 || y <= 120 || nearEnd) bottom.classList.remove('translate-y-full');
                }
                // Homepage, phones: keep the floating WhatsApp button off the hero's
                // "Order Now" button until the shopper scrolls past it.
                var wa = document.getElementById('wa-toggle');
                if (wa && document.querySelector('[data-hero]')) {
                    ['max-md:translate-x-24', 'max-md:opacity-0', 'max-md:pointer-events-none'].forEach(function (c) {
                        wa.classList.toggle(c, y < 400);
                    });
                }
                lastY = y;
                ticking = false;
            }
            window.addEventListener('scroll', function () {
                if (!ticking) { ticking = true; requestAnimationFrame(update); }
            }, { passive: true });
            update();
            // The WhatsApp button is rendered after this script; apply state once it exists.
            document.addEventListener('DOMContentLoaded', update);
        })();
    </script>
    @stack('script')


    <!-- Floating WhatsApp Button -->
    <x-shop.whatsapp-chat />
</body>
</html>
