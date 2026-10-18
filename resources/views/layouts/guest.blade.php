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
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">

    @if ($code = config('tracking.google_site_verification'))
        <meta name="google-site-verification" content="{{ $code }}">
    @endif
    @if ($code = config('tracking.bing_site_verification'))
        <meta name="msvalidate.01" content="{{ $code }}">
    @endif

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

    @if ($pixelId = config('tracking.meta_pixel_id'))
        <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', @json($pixelId));
            fbq('track', 'PageView');
        </script>
    @endif

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
<body data-auth="{{ Auth::check() ? '1' : '0' }}">
    <div class="z-40 md:sticky md:top-0">
        <x-shop.header :cart-count="collect(session('cart', []))->sum('quantity')" />
        <x-shop.nav />
    </div>

    <main>
        @yield('content')
    </main>

    <x-shop.footer />

    <script>
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

        // GA4 add_to_cart: fires client-side on submit so it works even though
        // "Add to cart" is a normal (non-AJAX) form POST that reloads the page.
        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form.matches('form[action="{{ route('cart.store') }}"]')) return;
            if (typeof gtag !== 'function') return;

            var productId = form.querySelector('[name="product_id"]')?.value;
            var quantity = form.querySelector('[name="quantity"]')?.value;
            if (!productId) return;

            gtag('event', 'add_to_cart', {
                items: [{ item_id: productId, quantity: Number(quantity) || 1 }]
            });
        });
    </script>
    @stack('script')



    <!-- Floating WhatsApp Button -->
    <x-shop.whatsapp-chat />
</body>
</html>
