{{--
    Responsive product image: a plain <img> whose src AND srcset are WebP
    (240/480/800/full, see App\Support\WebpImage); the original upload is only
    used when no WebP exists yet. No <picture> wrapper, so the URL the browser
    picks is exactly what <x-preload-image> preloads (same srcset + sizes).

    <x-picture :path="$product->product_image" sizes="(min-width: 768px) 25vw, 46vw"
               :priority="$loop->first" class="aspect-square w-full object-contain" alt="…" />

    priority = the page's LCP image: eager + fetchpriority=high (pair it with
    <x-preload-image> using the same path and sizes). Everything else is lazy.
--}}
@props(['path' => null, 'sizes' => '100vw', 'alt' => '', 'width' => 600, 'height' => 600, 'priority' => false, 'fallback' => 'images/product-placeholder.svg'])

@php
    $set = \App\Support\WebpImage::responsive($path);
    $src = $set['src'] ?? ($path ? asset('storage/' . $path) : asset($fallback));
@endphp

<img src="{{ $src }}" @if ($set) srcset="{{ $set['srcset'] }}" sizes="{{ $sizes }}" @endif
     alt="{{ $alt }}" width="{{ $width }}" height="{{ $height }}" decoding="async"
     @if ($priority) loading="eager" fetchpriority="high" @else loading="lazy" @endif
     {{ $attributes }}>
