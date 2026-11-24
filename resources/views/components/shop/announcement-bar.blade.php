{{--
    Rotating store promises (every 4s), dismissible for the browser session.
    The free-delivery message only appears when the admin has set a
    threshold — and checkout then really charges Rs. 0 (App\Support\Shipping).
    No layout shift on dismiss-persisted pages: an inline <head> script adds
    html.ann-off before first paint (see layouts/guest.blade.php).
--}}
@php
    use App\Support\Settings;

    $messages = [];
    if ($threshold = Settings::freeDeliveryThreshold()) {
        $messages[] = ['truck', 'Free delivery on orders above ' . money($threshold)];
    }
    $messages[] = ['banknote', 'Cash on Delivery — open the parcel and check before you pay'];
    $messages[] = ['rotate-ccw', '14-day easy returns'];
    $messages[] = ['map-pin', 'Delivery all over Pakistan in 1–3 working days'];
@endphp

@if (Settings::bool('announcement_enabled'))
    <div id="announcement" class="announcement relative bg-ink text-white" role="region" aria-label="Store announcements">
        <div class="container-shop flex h-9 items-center justify-center pr-10">
            <ul class="relative h-9 w-full overflow-hidden text-center text-xs font-bold sm:text-[13px]" aria-live="off">
                @foreach ($messages as $i => [$icon, $text])
                    <li data-ann-item class="absolute inset-0 flex items-center justify-center gap-2 transition-[transform,opacity] duration-500 ease-soft {{ $i === 0 ? '' : 'translate-y-full opacity-0' }}"
                        @if ($i > 0) aria-hidden="true" @endif>
                        <x-shop.icon :name="$icon" class="size-4 shrink-0 text-butter" />
                        <span class="truncate">{{ $text }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <button type="button" data-ann-close
                class="absolute right-1 top-1/2 grid size-9 -translate-y-1/2 place-items-center rounded-full text-white/80 hover:bg-white/10 hover:text-white"
                aria-label="Close announcements">
            <x-shop.icon name="x" class="size-4" />
        </button>
    </div>
    <script>
        (function () {
            var bar = document.getElementById('announcement');
            if (!bar) return;
            var items = bar.querySelectorAll('[data-ann-item]');
            var i = 0, timer = null;
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            function show(next) {
                items[i].classList.add('-translate-y-full', 'opacity-0');
                items[i].setAttribute('aria-hidden', 'true');
                var n = items[next];
                n.classList.remove('-translate-y-full');
                n.classList.add('translate-y-full', 'opacity-0');
                void n.offsetWidth; // restart from below
                n.classList.remove('translate-y-full', 'opacity-0');
                n.removeAttribute('aria-hidden');
                i = next;
            }
            function start() { if (!reduce && items.length > 1 && !timer) timer = setInterval(function () { show((i + 1) % items.length); }, 4000); }
            function stop() { clearInterval(timer); timer = null; }

            bar.addEventListener('mouseenter', stop);
            bar.addEventListener('mouseleave', start);
            bar.addEventListener('focusin', stop);
            bar.addEventListener('focusout', start);
            document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });
            start();

            bar.querySelector('[data-ann-close]').addEventListener('click', function () {
                stop();
                document.documentElement.classList.add('ann-off');
                try { sessionStorage.setItem('ann-off', '1'); } catch (e) {}
            });
        })();
    </script>
@endif
