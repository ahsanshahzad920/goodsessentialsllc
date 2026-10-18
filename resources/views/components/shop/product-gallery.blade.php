@props(['product'])

@php
    $images = $product->images->pluck('path')->map(fn ($path) => asset('storage/' . $path))->values();

    // products without gallery rows: fall back to the single cover image, then to a placeholder
    if ($images->isEmpty() && $product->product_image) {
        $images = collect([asset('storage/' . $product->product_image)]);
    }
    if ($images->isEmpty()) {
        $images = collect([asset('images/product-placeholder.svg')]);
    }

    $alt   = $product->product_title ?? 'Product image';
    $multi = $images->count() > 1;
@endphp

<div data-gallery class="space-y-3">
    <div data-gallery-stage @if ($multi) tabindex="0" @endif
         style="touch-action: pan-y;"
         aria-roledescription="carousel" aria-label="Product images"
         class="relative overflow-hidden rounded-2xl border border-line bg-white p-2 outline-none focus-visible:ring-2 focus-visible:ring-brand-500/40 sm:p-3">

        <div class="flex min-h-[240px] items-center justify-center rounded-2xl bg-mist-100 p-3 sm:min-h-[320px] lg:min-h-[350px]">
            <img data-gallery-main src="{{ $images->first() }}" alt="{{ $alt }}" draggable="false"
                class="max-h-[280px] w-full select-none object-contain transition-opacity duration-150 sm:max-h-[320px] lg:max-h-[340px]" fetchpriority="high">
        </div>

        @if ($multi)
            <button type="button" data-gallery-prev aria-label="Previous image"
                    class="absolute left-3 top-1/2 grid size-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-ink shadow-md transition hover:bg-brand-500 hover:text-white focus-visible:ring-2 focus-visible:ring-brand-500/40 sm:left-5 sm:size-11">
                <x-shop.icon name="chevron-down" class="size-5 rotate-90" />
            </button>

            <button type="button" data-gallery-next aria-label="Next image"
                    class="absolute right-3 top-1/2 grid size-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-ink shadow-md transition hover:bg-brand-500 hover:text-white focus-visible:ring-2 focus-visible:ring-brand-500/40 sm:right-5 sm:size-11">
                <x-shop.icon name="chevron-down" class="size-5 -rotate-90" />
            </button>

            <span data-gallery-count aria-live="polite"
                  class="absolute bottom-4 right-4 rounded-full bg-black/60 px-3 py-1 text-[11px] font-bold text-white sm:bottom-6 sm:right-6">
                1 / {{ $images->count() }}
            </span>
        @endif
    </div>

    @if ($multi)
        <ul class="flex flex-wrap gap-2" aria-label="Product thumbnails">
            @foreach ($images as $i => $src)
                <li>
                    <button type="button" data-gallery-thumb data-src="{{ $src }}"
                            aria-label="Show image {{ $i + 1 }}" aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                            class="block size-16 overflow-hidden rounded-xl border-2 border-line bg-white transition hover:border-brand-500/50 aria-[current=true]:border-brand-500 sm:size-20">
                        <img src="{{ $src }}" alt="" loading="lazy" class="size-full object-cover">
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>

@pushOnce('script')
<script>
    (function () {
        function thumbsOf(gallery) {
            return Array.prototype.slice.call(gallery.querySelectorAll('[data-gallery-thumb]'));
        }

        function currentIndex(thumbs) {
            for (var i = 0; i < thumbs.length; i++) {
                if (thumbs[i].getAttribute('aria-current') === 'true') return i;
            }
            return 0;
        }

        function show(gallery, index) {
            var thumbs = thumbsOf(gallery);
            if (!thumbs.length) return;

            index = (index + thumbs.length) % thumbs.length; // wrap around
            var thumb = thumbs[index];
            var main = gallery.querySelector('[data-gallery-main]');

            // preload, then swap with a small fade
            main.style.opacity = '0.4';
            var preload = new Image();
            preload.onload = function () { main.src = thumb.dataset.src; main.style.opacity = '1'; };
            preload.onerror = function () { main.src = thumb.dataset.src; main.style.opacity = '1'; };
            preload.src = thumb.dataset.src;

            thumbs.forEach(function (t, i) { t.setAttribute('aria-current', i === index ? 'true' : 'false'); });

            var counter = gallery.querySelector('[data-gallery-count]');
            if (counter) counter.textContent = (index + 1) + ' / ' + thumbs.length;

            thumb.scrollIntoView({ block: 'nearest', inline: 'nearest' });
        }

        function step(gallery, direction) {
            show(gallery, currentIndex(thumbsOf(gallery)) + direction);
        }

        // Click: thumbnails and arrow buttons
        document.addEventListener('click', function (e) {
            var gallery = e.target.closest('[data-gallery]');
            if (!gallery) return;

            var thumb = e.target.closest('[data-gallery-thumb]');
            if (thumb) { show(gallery, thumbsOf(gallery).indexOf(thumb)); return; }

            if (e.target.closest('[data-gallery-prev]')) step(gallery, -1);
            if (e.target.closest('[data-gallery-next]')) step(gallery, 1);
        });

        // Keyboard: left / right arrows while the image area is focused
        document.addEventListener('keydown', function (e) {
            var stage = e.target.closest && e.target.closest('[data-gallery-stage]');
            if (!stage) return;

            if (e.key === 'ArrowLeft')  { e.preventDefault(); step(stage.closest('[data-gallery]'), -1); }
            if (e.key === 'ArrowRight') { e.preventDefault(); step(stage.closest('[data-gallery]'), 1); }
        });

        // Touch: swipe left / right
        var startX = null, startY = null;

        document.addEventListener('touchstart', function (e) {
            if (!e.target.closest('[data-gallery-stage]')) { startX = null; return; }
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        }, { passive: true });

        document.addEventListener('touchend', function (e) {
            if (startX === null) return;

            var stage = e.target.closest('[data-gallery-stage]');
            var dx = e.changedTouches[0].clientX - startX;
            var dy = e.changedTouches[0].clientY - startY;
            startX = null;

            if (!stage || Math.abs(dx) < 40 || Math.abs(dx) < Math.abs(dy)) return; // ignore taps and vertical scrolls

            step(stage.closest('[data-gallery]'), dx < 0 ? 1 : -1);
        });
    })();
</script>
@endPushOnce
