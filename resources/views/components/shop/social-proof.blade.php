{{--
    "Ayesha from Multan ordered … — 2 hours ago" (App\Support\RecentOrders).
    Real orders only, first name + city only, max 5 per browser session,
    admin toggle in Store Settings. Not shown on cart / checkout / thank-you
    pages so nothing distracts from placing the order.
--}}
@unless (request()->routeIs('cart', 'checkout', 'checkout.*', 'order.success', 'payment.*') || ! \App\Support\RecentOrders::enabled())
<div id="social-proof" class="pointer-events-none fixed bottom-[calc(4.75rem+env(safe-area-inset-bottom))] left-3 z-[45] w-[min(20rem,calc(100vw-6rem))] md:bottom-6 md:left-6" aria-live="polite"></div>
<script>
(function () {
    var KEY = 'sp-shown', MAX = 5;
    var box = document.getElementById('social-proof');
    var shown = 0;
    try { shown = +sessionStorage.getItem(KEY) || 0; if (sessionStorage.getItem('sp-off')) return; } catch (e) {}
    if (shown >= MAX) return;

    var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    var feed = [], i = 0, timer;

    function card(o) {
        var a = document.createElement('a');
        a.href = o.url;
        a.className = 'pointer-events-auto flex items-center gap-3 rounded-2xl border border-line bg-white p-2.5 pr-8 shadow-lift transition-[transform,opacity] duration-400 ease-soft ' + (reduce ? '' : 'translate-y-3 opacity-0');
        var img = document.createElement('img');
        img.src = o.image; img.alt = ''; img.width = 48; img.height = 48;
        img.className = 'size-12 shrink-0 rounded-xl bg-frame object-contain p-0.5';
        var t = document.createElement('span');
        t.className = 'min-w-0 text-xs leading-snug text-muted';
        var who = document.createElement('strong'); who.className = 'text-ink'; who.textContent = o.name + (o.city ? ' from ' + o.city : '');
        var what = document.createElement('span'); what.className = 'line-clamp-2'; what.textContent = 'ordered ' + o.product;
        var when = document.createElement('span'); when.className = 'mt-0.5 block text-[11px]'; when.textContent = o.ago;
        t.append(who, document.createTextNode(' '), what, when);
        var x = document.createElement('button');
        x.type = 'button'; x.className = 'absolute right-1 top-1 grid size-8 place-items-center rounded-full text-muted hover:bg-mist-100';
        x.setAttribute('aria-label', 'Hide recent orders'); x.textContent = '×';
        x.addEventListener('click', function (e) { e.preventDefault(); clearTimeout(timer); box.replaceChildren(); try { sessionStorage.setItem('sp-off', '1'); } catch (_) {} });
        var wrap = document.createElement('div'); wrap.className = 'relative';
        a.append(img, t); wrap.append(a, x);
        return wrap;
    }

    function next() {
        if (shown >= MAX || i >= feed.length) return;
        var el = card(feed[i++]);
        box.replaceChildren(el);
        var inner = el.firstChild;
        requestAnimationFrame(function () { inner.classList.remove('translate-y-3', 'opacity-0'); });
        try { sessionStorage.setItem(KEY, ++shown); } catch (e) {}
        timer = setTimeout(function () {
            inner.classList.add('translate-y-3', 'opacity-0');
            setTimeout(function () { box.replaceChildren(); timer = setTimeout(next, 14000); }, 400);
        }, 6000);
    }

    setTimeout(function () {
        fetch(@json(route('recent-orders')), { headers: { Accept: 'application/json' } })
            .then(function (r) { return r.ok ? r.json() : []; })
            .then(function (d) { feed = Array.isArray(d) ? d : []; next(); })
            .catch(function () {});
    }, 12000);
})();
</script>
@endunless
