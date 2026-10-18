<!DOCTYPE html>
<html lang="en-PK">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! \Artesaos\SEOTools\Facades\SEOMeta::generate() !!}
    {!! \Artesaos\SEOTools\Facades\OpenGraph::generate() !!}
    {!! \Artesaos\SEOTools\Facades\TwitterCard::generate() !!}
    {!! \Artesaos\SEOTools\Facades\JsonLdMulti::generate() !!}
    <link rel="shortcut icon" href="{{ asset('img/fav.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">

    @vite(['resources/css/app.css'])
    @stack('style')
    
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
    </script>
    @stack('script')



    <!-- Floating WhatsApp Button -->
    <x-shop.whatsapp-chat />
</body>
</html>
