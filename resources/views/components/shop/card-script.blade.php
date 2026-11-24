{{--
    Product-card behaviour, loaded once per page (layouts/guest):
    - gallery dots follow swipes; with a mouse, hovering shows the 2nd image
    - form[data-ajax-cart]: add without leaving the page, fly-to-cart,
      badge bounce, "Added ✓ View cart" toast. Any error falls back to a
      normal form post, so adding to cart can never silently fail.
    - wishlist heart "pop"
    Animations use transform/opacity only and are skipped for reduced motion.
--}}
<div id="toast-region" class="pointer-events-none fixed inset-x-0 bottom-[calc(4.75rem+env(safe-area-inset-bottom))] z-[60] flex justify-center px-4 md:bottom-6" aria-live="polite" role="status"></div>

<script>
(function () {
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    // ---- Gallery dots + hover swap ------------------------------------------
    function syncDots(g) {
        var i = Math.round(g.scrollLeft / Math.max(1, g.clientWidth));
        g.parentElement.querySelectorAll('[data-card-dot]').forEach(function (d, k) {
            d.classList.toggle('w-4', k === i); d.classList.toggle('bg-ink/60', k === i);
            d.classList.toggle('w-1.5', k !== i); d.classList.toggle('bg-ink/20', k !== i);
        });
    }
    document.querySelectorAll('[data-card-gallery]').forEach(function (g) {
        var t; g.addEventListener('scroll', function () { clearTimeout(t); t = setTimeout(function () { syncDots(g); }, 60); }, { passive: true });
        if (canHover) {
            var card = g.closest('article');
            card.addEventListener('mouseenter', function () { g.scrollTo({ left: g.clientWidth, behavior: reduce ? 'auto' : 'smooth' }); });
            card.addEventListener('mouseleave', function () { g.scrollTo({ left: 0, behavior: reduce ? 'auto' : 'smooth' }); });
        }
    });

    // ---- 3D tilt + glare (mouse only, max 6°) --------------------------------
    if (canHover && !reduce) {
        document.querySelectorAll('article.tilt').forEach(function (card) {
            var raf = 0;
            card.addEventListener('pointermove', function (e) {
                cancelAnimationFrame(raf);
                raf = requestAnimationFrame(function () {
                    var r = card.getBoundingClientRect();
                    var x = (e.clientX - r.left) / r.width, y = (e.clientY - r.top) / r.height;
                    card.style.setProperty('--ry', ((x - 0.5) * 12).toFixed(2) + 'deg');
                    card.style.setProperty('--rx', ((0.5 - y) * 12).toFixed(2) + 'deg');
                    card.style.setProperty('--gx', (x * 100).toFixed(0) + '%');
                    card.style.setProperty('--gy', (y * 100).toFixed(0) + '%');
                    card.classList.add('is-tilting');
                });
            });
            card.addEventListener('pointerleave', function () {
                cancelAnimationFrame(raf);
                card.classList.remove('is-tilting');
                card.style.setProperty('--rx', '0deg'); card.style.setProperty('--ry', '0deg');
            });
        });
    }

    // ---- Toast ---------------------------------------------------------------
    var region = document.getElementById('toast-region'), toastTimer;
    window.shopToast = function (text, link) {
        region.replaceChildren();
        var box = document.createElement('div');
        box.className = 'pointer-events-auto flex items-center gap-3 rounded-full bg-ink py-2 pl-4 pr-2 text-sm font-bold text-white shadow-lift transition-[transform,opacity] duration-300 ease-soft translate-y-4 opacity-0';
        if (link && link.openCart) {
            // Little drawn checkmark (replaces a Lottie "success" animation, no library needed).
            box.insertAdjacentHTML('beforeend', '<svg class="size-5 shrink-0 text-mint" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5" stroke-dasharray="24" class="animate-draw"/></svg>');
        }
        var msg = document.createElement('span'); msg.textContent = text; box.appendChild(msg);
        if (link) {
            var a = document.createElement('a');
            a.href = link.href; a.textContent = link.text;
            if (link.openCart) a.setAttribute('data-open-cart', '');
            a.className = 'inline-flex min-h-9 items-center rounded-full bg-white px-4 text-ink hover:bg-cream';
            box.appendChild(a);
        }
        region.appendChild(box);
        requestAnimationFrame(function () { box.classList.remove('translate-y-4', 'opacity-0'); });
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { box.classList.add('translate-y-4', 'opacity-0'); setTimeout(function () { box.remove(); }, 300); }, 3500);
    };

    // ---- Cart badges ---------------------------------------------------------
    window.setCartCount = function (n) {
        document.querySelectorAll('[data-cart-count]').forEach(function (b) {
            b.textContent = n;
            b.classList.toggle('hidden', !n); b.classList.toggle('grid', !!n);
            b.classList.remove('animate-cart-bounce'); void b.offsetWidth; b.classList.add('animate-cart-bounce');
        });
    };

    // Visible cart icon to fly to: bottom nav on phones, header otherwise.
    function cartTarget() {
        var cands = document.querySelectorAll('#bottom-nav a[href$="/cart"], header a[href$="/cart"]');
        for (var i = 0; i < cands.length; i++) { var r = cands[i].getBoundingClientRect(); if (r.width && r.bottom > 0 && r.top < innerHeight) return cands[i]; }
        return null;
    }

    function fly(fromImg) {
        var target = cartTarget();
        if (reduce || !fromImg || !target || !fromImg.animate) return;
        var a = fromImg.getBoundingClientRect(), b = target.getBoundingClientRect();
        var ghost = fromImg.cloneNode();
        ghost.removeAttribute('loading'); ghost.removeAttribute('fetchpriority');
        ghost.style.cssText = 'position:fixed;z-index:70;pointer-events:none;object-fit:contain;border-radius:16px;background:#fff7f2;' +
            'left:' + a.left + 'px;top:' + a.top + 'px;width:' + a.width + 'px;height:' + a.height + 'px;';
        document.body.appendChild(ghost);
        var dx = b.left + b.width / 2 - (a.left + a.width / 2), dy = b.top + b.height / 2 - (a.top + a.height / 2);
        ghost.animate([
            { transform: 'translate(0,0) scale(1)', opacity: 1 },
            { transform: 'translate(' + dx * 0.5 + 'px,' + (dy * 0.5 - 60) + 'px) scale(.45)', opacity: .9, offset: .55 },
            { transform: 'translate(' + dx + 'px,' + dy + 'px) scale(.08)', opacity: .2 }
        ], { duration: 650, easing: 'cubic-bezier(.2,.8,.2,1)' }).onfinish = function () { ghost.remove(); };
    }

    // ---- AJAX add to cart ----------------------------------------------------
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form.matches('form[data-ajax-cart]') || form.dataset.fallback) return;
        e.preventDefault(); // the AddToCart pixel listener still runs (same submit event)

        var btn = form.querySelector('button[type="submit"]');
        if (btn) { btn.setAttribute('data-loading', ''); btn.setAttribute('aria-busy', 'true'); }

        fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form)
        }).then(function (r) {
            return r.json().catch(function () { return {}; }).then(function (d) { return { status: r.status, data: d }; });
        }).then(function (res) {
            if (res.status === 200 && res.data.ok) {
                fly(form.closest('article') && form.closest('article').querySelector('img'));
                setTimeout(function () { setCartCount(res.data.count); }, reduce ? 0 : 550);
                shopToast('Added to cart', { href: res.data.cart_url, text: 'View cart', openCart: true });
                document.dispatchEvent(new CustomEvent('cart:changed', { detail: res.data }));
            } else if (res.status === 422 && res.data.message) {
                shopToast(res.data.message);
            } else {
                throw new Error('unexpected');
            }
        }).catch(function () {
            form.dataset.fallback = '1'; // let the browser post it normally
            form.submit();
        }).finally(function () {
            if (btn) { btn.removeAttribute('data-loading'); btn.removeAttribute('aria-busy'); }
        });
    });

    // ---- Wishlist heart pop (the toggle itself lives in layouts/guest) -------
    document.addEventListener('click', function (e) {
        var heart = e.target.closest('.js-wishlist');
        if (!heart || reduce || document.body.dataset.auth !== '1') return;
        heart.classList.remove('animate-heart-pop'); void heart.offsetWidth; heart.classList.add('animate-heart-pop');
    });
})();
</script>
