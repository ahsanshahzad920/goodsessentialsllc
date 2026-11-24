{{--
    Preload a page's LCP image so the browser fetches it with the HTML, before
    CSS/JS are parsed. Use with the same path/sizes as the <x-picture priority>.
    Pushed into the <head> via @push('preload') in layouts/guest.
--}}
@props(['path' => null, 'sizes' => '100vw'])

@if ($path)
    @push('preload')
        @if ($srcset = \App\Support\WebpImage::srcset($path))
            <link rel="preload" as="image" type="image/webp" imagesrcset="{{ $srcset }}" imagesizes="{{ $sizes }}" fetchpriority="high">
        @else
            <link rel="preload" as="image" href="{{ asset('storage/' . $path) }}" fetchpriority="high">
        @endif
    @endpush
@endif
