{{--
    Slide-in cart: right drawer on desktop, bottom sheet on phones.
    Opens from the header / bottom-nav cart icons and the "View cart" toast
    instead of jumping to /cart (the links still work without JavaScript).
    Content comes from GET /cart/drawer and is refreshed after every change.
--}}
<x-bottom-sheet id="cart-drawer" title="Your cart" side="right">
    <div data-drawer-body aria-live="polite" class="min-h-full">
        {{-- Skeleton while loading (no spinners) --}}
        <div class="space-y-4" aria-hidden="true">
            @for ($i = 0; $i < 3; $i++)
                <div class="flex gap-3"><div class="size-18 animate-pulse rounded-xl bg-mist-100"></div><div class="flex-1 space-y-2 py-1"><div class="h-3 w-3/4 animate-pulse rounded bg-mist-100"></div><div class="h-3 w-1/3 animate-pulse rounded bg-mist-100"></div></div></div>
            @endfor
        </div>
    </div>
</x-bottom-sheet>

<script>
(function () {
    var urls = {
        drawer: @json(route('cart.drawer')),
        increment: @json(route('increment.session')),
        decrement: @json(route('decrement.session')),
        remove: @json(route('cart.remove')),
        cartPage: @json(route('cart'))
    };
    var body = document.querySelector('[data-drawer-body]');
    var token = document.querySelector('meta[name="csrf-token"]').content;

    function load() {
        return fetch(urls.drawer, { headers: { Accept: 'text/html' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                body.innerHTML = html; // our own server-rendered partial
                var c = body.querySelector('[data-drawer-count]');
                if (c && window.setCartCount) setCartCount(+c.dataset.drawerCount);
            });
    }
    window.openCart = function () { window.sheet.open('cart-drawer'); load(); };

    // Cart icons + "View cart" links open the drawer (except on the cart page itself).
    document.addEventListener('click', function (e) {
        var a = e.target.closest('a[data-open-cart], header a[href="' + urls.cartPage + '"], #bottom-nav a[href="' + urls.cartPage + '"]');
        if (!a || location.href.split(/[?#]/)[0] === urls.cartPage || e.metaKey || e.ctrlKey) return;
        e.preventDefault();
        openCart();
    });

    // +, −, remove inside the drawer.
    body.addEventListener('click', function (e) {
        var b = e.target.closest('[data-drawer-action]');
        if (!b) return;
        b.disabled = true;
        var fd = new FormData();
        fd.append('_token', token);
        fd.append('item_key', b.dataset.key);
        fetch(urls[b.dataset.drawerAction], { method: 'POST', headers: { Accept: 'application/json' }, body: fd })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
            .then(function (res) { if (!res.ok && res.d.message && window.shopToast) shopToast(res.d.message); })
            .catch(function () {})
            .finally(load);
    });

    // Something was added (cards, add-ons): refresh if the drawer is open.
    document.addEventListener('cart:changed', function () {
        if (document.getElementById('cart-drawer').open) load();
    });
})();
</script>
