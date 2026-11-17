{{--
    Instant search for every [data-search] box (desktop header + mobile dialog).
    - 250ms debounce, aborts stale requests, caches answers per query
    - empty box: real popular searches (if any) + categories
    - arrow keys / Enter / Escape, combobox ARIA
    - all text inserted with textContent (no HTML injection from product data)
    - fires Meta/TikTok "Search" on submit
--}}
<script>
(function () {
    var endpoint = @json(route('search.suggest'));
    var cache = new Map();

    function el(tag, cls, text) {
        var n = document.createElement(tag);
        if (cls) n.className = cls;
        if (text != null) n.textContent = text;
        return n;
    }

    function chips(title, items) {
        var wrap = el('div', 'px-2 py-2');
        wrap.appendChild(el('p', 'mb-2 text-xs font-extrabold uppercase tracking-wider text-muted', title));
        var list = el('div', 'flex flex-wrap gap-2');
        items.forEach(function (it) {
            var a = el('a', 'inline-flex min-h-10 items-center rounded-full bg-brand-50 px-4 text-sm font-bold text-brand-700 hover:bg-brand-100', it.name);
            a.href = it.url;
            a.setAttribute('role', 'option');
            list.appendChild(a);
        });
        wrap.appendChild(list);
        return wrap;
    }

    function render(panel, data, q) {
        panel.replaceChildren();
        if (!q || q.length < 2) {
            if (data.popular && data.popular.length) {
                panel.appendChild(chips('Popular searches', data.popular.map(function (t) {
                    return { name: t, url: @json(route('shop')) + '?q=' + encodeURIComponent(t) };
                })));
            }
            if (data.categories && data.categories.length) panel.appendChild(chips('Shop by category', data.categories));
            return;
        }
        if (!data.products.length) {
            panel.appendChild(el('p', 'px-3 py-4 text-sm text-muted', 'No products found for “' + q + '”.'));
            return;
        }
        var list = el('ul', 'divide-y divide-line');
        list.setAttribute('role', 'listbox');
        data.products.forEach(function (p) {
            var li = el('li');
            var a = el('a', 'flex items-center gap-3 rounded-xl p-2 hover:bg-mist-100 aria-selected:bg-mist-100');
            a.href = p.url;
            a.setAttribute('role', 'option');
            var img = el('img', 'size-14 shrink-0 rounded-xl bg-frame object-contain p-1 mix-blend-multiply');
            img.src = p.image; img.alt = ''; img.width = 56; img.height = 56; img.loading = 'lazy';
            var body = el('span', 'min-w-0 flex-1');
            body.appendChild(el('span', 'line-clamp-2 text-sm font-bold leading-snug text-ink', p.title));
            var price = el('span', 'mt-0.5 flex items-baseline gap-1.5 text-sm');
            price.appendChild(el('span', 'font-extrabold ' + (p.was ? 'text-coral-600' : 'text-ink'), p.price));
            if (p.was) price.appendChild(el('span', 'text-xs text-muted line-through', p.was));
            body.appendChild(price);
            a.append(img, body);
            li.appendChild(a);
            list.appendChild(li);
        });
        panel.appendChild(list);
        var all = el('a', 'mt-1 flex min-h-11 items-center justify-center rounded-xl bg-brand-50 text-sm font-extrabold text-brand-700 hover:bg-brand-100',
            'See all ' + data.total + ' result' + (data.total === 1 ? '' : 's') + ' for “' + q + '”');
        all.href = data.all_url;
        all.setAttribute('role', 'option');
        panel.appendChild(all);
    }

    function bind(box) {
        var input = box.querySelector('[data-search-input]');
        var panel = box.querySelector('[data-search-panel]');
        var form = box.querySelector('form');
        var inDialog = !!box.closest('dialog');
        var timer, ctrl, active = -1;
        if (!input || !panel) return;

        function setOpen(open) {
            if (!inDialog) panel.hidden = !open;
            input.setAttribute('aria-expanded', String(open));
            if (!open) active = -1;
        }

        function load() {
            var q = input.value.trim();
            var key = q.length < 2 ? '' : q.toLowerCase();
            if (cache.has(key)) { render(panel, cache.get(key), q); setOpen(true); return; }
            if (ctrl) ctrl.abort();
            ctrl = new AbortController();
            box.setAttribute('aria-busy', 'true');
            fetch(endpoint + '?q=' + encodeURIComponent(q), { headers: { Accept: 'application/json' }, signal: ctrl.signal })
                .then(function (r) { return r.ok ? r.json() : Promise.reject(r.status); })
                .then(function (data) { cache.set(key, data); if (input.value.trim() === q) { render(panel, data, q); setOpen(true); } })
                .catch(function () {})
                .finally(function () { box.removeAttribute('aria-busy'); });
        }

        input.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(load, 250); });
        input.addEventListener('focus', load);

        input.addEventListener('keydown', function (e) {
            var opts = panel.querySelectorAll('[role="option"]');
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                if (!opts.length) return;
                e.preventDefault();
                if (active >= 0) opts[active].removeAttribute('aria-selected');
                active = (active + (e.key === 'ArrowDown' ? 1 : -1) + opts.length) % opts.length;
                opts[active].setAttribute('aria-selected', 'true');
                opts[active].scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'Enter' && active >= 0 && opts[active]) {
                e.preventDefault();
                window.location.href = opts[active].href;
            } else if (e.key === 'Escape' && !inDialog) {
                setOpen(false);
            }
        });

        if (!inDialog) {
            document.addEventListener('click', function (e) { if (!box.contains(e.target)) setOpen(false); });
        }

        form.addEventListener('submit', function () {
            var q = input.value.trim();
            if (!q) return;
            if (window.fbqTrack) fbqTrack('Search', { search_string: q });
            if (window.ttqTrack) ttqTrack('Search', { query: q });
        });
    }

    document.querySelectorAll('[data-search]').forEach(bind);

    // Mobile: open the full-screen search and focus its box.
    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-search-open]')) return;
        e.preventDefault();
        var d = document.getElementById('search-dialog');
        if (!d || !window.sheet) return;
        window.sheet.open('search-dialog');
        var input = d.querySelector('[data-search-input]');
        if (input) input.focus(); // the focus handler loads suggestions
    });
})();
</script>
