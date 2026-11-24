@props(['product'])

{{--
    Product gallery: swipeable (scroll-snap) main images with dots, thumbnails
    from sm up, and a tap-to-open lightbox where pinch-zoom works natively.
    First image is the LCP element: eager + fetchpriority=high, WebP when present.
--}}
@php
    use App\Support\WebpImage;

    $paths = $product->images->pluck('path')->values();
    if ($paths->isEmpty() && $product->product_image) {
        $paths = collect([$product->product_image]);
    }
    $images = $paths->map(fn ($p) => ['src' => asset('storage/' . $p), 'webp' => WebpImage::url($p)])->values();
    if ($images->isEmpty()) {
        $images = collect([['src' => asset('images/product-placeholder.svg'), 'webp' => null]]);
    }
    $alt = $product->product_title ?? 'Product image';
    $multi = $images->count() > 1;
@endphp

<div data-pgallery class="min-w-0 space-y-3">
    <div class="relative overflow-hidden rounded-card border border-line bg-frame">
        <ul data-pg-track class="flex snap-x snap-mandatory overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
            aria-label="Product images" @if ($multi) aria-roledescription="carousel" @endif>
            @foreach ($images as $i => $img)
                <li class="w-full shrink-0 snap-center" @if ($multi) aria-label="Image {{ $i + 1 }} of {{ $images->count() }}" @endif>
                    <button type="button" data-pg-zoom="{{ $i }}" class="block w-full cursor-zoom-in" aria-label="Zoom image {{ $i + 1 }}">
                        <picture>
                            @if ($img['webp']) <source type="image/webp" srcset="{{ $img['webp'] }}"> @endif
                            <img src="{{ $img['src'] }}" alt="{{ $i === 0 ? $alt : '' }}" width="800" height="800" decoding="async" draggable="false"
                                 @if ($i === 0) loading="eager" fetchpriority="high" @else loading="lazy" @endif
                                 class="aspect-square max-h-[42vh] w-full select-none object-contain p-4 mix-blend-multiply sm:max-h-none sm:p-6">
                        </picture>
                    </button>
                </li>
            @endforeach
        </ul>

        @if ($multi)
            <div class="pointer-events-none absolute inset-x-0 bottom-3 flex justify-center gap-1.5" aria-hidden="true">
                @foreach ($images as $i => $img)
                    <span data-pg-dot class="h-2 rounded-full transition-[width,background-color] duration-300 {{ $i === 0 ? 'w-5 bg-ink/60' : 'w-2 bg-ink/20' }}"></span>
                @endforeach
            </div>
            <button type="button" data-pg-prev aria-label="Previous image"
                    class="absolute left-3 top-1/2 hidden size-11 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-ink shadow-soft hover:bg-white sm:grid">
                <x-shop.icon name="chevron-left" class="size-5" />
            </button>
            <button type="button" data-pg-next aria-label="Next image"
                    class="absolute right-3 top-1/2 hidden size-11 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-ink shadow-soft hover:bg-white sm:grid">
                <x-shop.icon name="chevron-right" class="size-5" />
            </button>
        @endif
        <span class="pointer-events-none absolute right-3 top-3 grid size-9 place-items-center rounded-full bg-white/90 text-ink shadow-sm" aria-hidden="true">
            <x-shop.icon name="search" class="size-4" />
        </span>
    </div>

    @if ($multi)
        <ul class="hidden gap-2 overflow-x-auto sm:flex" aria-label="Product thumbnails">
            @foreach ($images as $i => $img)
                <li>
                    <button type="button" data-pg-thumb="{{ $i }}" aria-label="Show image {{ $i + 1 }}" aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                            class="block size-16 overflow-hidden rounded-xl border-2 border-line bg-frame transition hover:border-brand-500/50 aria-[current=true]:border-brand-600 md:size-20">
                        <img src="{{ $img['webp'] ?? $img['src'] }}" alt="" loading="lazy" width="80" height="80" class="size-full object-contain p-1 mix-blend-multiply">
                    </button>
                </li>
            @endforeach
        </ul>
    @endif

    {{-- Lightbox: native pinch-zoom on phones, scroll to zoom out on desktop --}}
    <dialog data-pg-lightbox aria-label="Product images, zoomed"
            class="fixed inset-0 m-0 h-dvh max-h-none w-full max-w-none bg-white p-0 backdrop:bg-ink/60 [&:not([open])]:hidden">
        <div class="flex h-full flex-col">
            <div class="flex shrink-0 items-center justify-between px-3 py-2">
                <span class="text-sm font-bold text-muted">Pinch or tap to zoom</span>
                <button type="button" data-pg-close class="grid size-11 place-items-center rounded-full hover:bg-mist-100" aria-label="Close">
                    <x-shop.icon name="x" class="size-6" />
                </button>
            </div>
            <ul data-pg-ltrack class="flex min-h-0 flex-1 snap-x snap-mandatory overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @foreach ($images as $img)
                    <li class="grid w-full shrink-0 snap-center place-items-center overflow-auto [touch-action:pan-x_pan-y_pinch-zoom]">
                        <img src="{{ $img['src'] }}" alt="" loading="lazy" data-pg-big
                             class="max-h-full w-full cursor-zoom-in object-contain transition-[width] duration-200">
                    </li>
                @endforeach
            </ul>
        </div>
    </dialog>
</div>

@pushOnce('script')
<script>
(function () {
    document.querySelectorAll('[data-pgallery]').forEach(function (g) {
        var track = g.querySelector('[data-pg-track]');
        var dots = g.querySelectorAll('[data-pg-dot]');
        var thumbs = g.querySelectorAll('[data-pg-thumb]');
        var box = g.querySelector('[data-pg-lightbox]');
        var ltrack = box.querySelector('[data-pg-ltrack]');
        var n = track.children.length, cur = 0;

        function go(i, smooth) { cur = (i + n) % n; track.scrollTo({ left: cur * track.clientWidth, behavior: smooth === false ? 'auto' : 'smooth' }); }
        function mark() {
            cur = Math.round(track.scrollLeft / Math.max(1, track.clientWidth));
            dots.forEach(function (d, k) { d.classList.toggle('w-5', k === cur); d.classList.toggle('bg-ink/60', k === cur); d.classList.toggle('w-2', k !== cur); d.classList.toggle('bg-ink/20', k !== cur); });
            thumbs.forEach(function (t, k) { t.setAttribute('aria-current', k === cur ? 'true' : 'false'); });
        }
        var t; track.addEventListener('scroll', function () { clearTimeout(t); t = setTimeout(mark, 60); }, { passive: true });
        thumbs.forEach(function (b) { b.addEventListener('click', function () { go(+b.dataset.pgThumb); }); });
        var prev = g.querySelector('[data-pg-prev]'), next = g.querySelector('[data-pg-next]');
        if (prev) prev.addEventListener('click', function () { go(cur - 1); });
        if (next) next.addEventListener('click', function () { go(cur + 1); });

        g.querySelectorAll('[data-pg-zoom]').forEach(function (b) {
            b.addEventListener('click', function () {
                box.showModal();
                ltrack.scrollTo({ left: (+b.dataset.pgZoom) * ltrack.clientWidth, behavior: 'auto' });
            });
        });
        box.querySelector('[data-pg-close]').addEventListener('click', function () { box.close(); });
        // Desktop: click to toggle a 2x zoom (phones pinch natively).
        box.querySelectorAll('[data-pg-big]').forEach(function (img) {
            img.addEventListener('click', function () {
                var on = img.style.width === '200%';
                img.style.width = on ? '' : '200%';
                img.style.maxHeight = on ? '' : 'none';
                img.classList.toggle('cursor-zoom-in', on); img.classList.toggle('cursor-zoom-out', !on);
            });
        });
        // Variant pickers can ask the gallery to show an image.
        g.addEventListener('pgallery:show', function (e) { go(e.detail || 0); });
    });
})();
</script>
@endPushOnce
