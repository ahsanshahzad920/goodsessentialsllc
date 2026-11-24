{{--
    Responsive product image: WebP srcset (240/480/800/full, see App\Support\WebpImage)
    with the original upload as the fallback <img src>.

    <x-picture :path="$product->product_image" sizes="(min-width: 768px) 25vw, 46vw"
               :priority="$loop->first" class="aspect-square w-full object-contain" alt="…" />

    priority = the page's LCP image: eager + fetchpriority=high (pair it with
    <x-preload-image> in the head). Everything else is lazy and async-decoded.
--}}
@props(['path' => null, 'sizes' => '100vw', 'alt' => '', 'width' => 600, 'height' => 600, 'priority' => false, 'fallback' => 'images/product-placeholder.svg'])

@php
    $srcset = \App\Support\WebpImage::srcset($path);
    $src = $path ? asset('storage/' . $path) : asset($fallback);
@endphp

<picture>
    @if ($srcset) <source type="image/webp" srcset="{{ $srcset }}" sizes="{{ $sizes }}"> @endif
    <img src="{{ $src }}" alt="{{ $alt }}" width="{{ $width }}" height="{{ $height }}" decoding="async"
         @if ($priority) loading="eager" fetchpriority="high" @else loading="lazy" @endif
         {{ $attributes }}>
</picture>
