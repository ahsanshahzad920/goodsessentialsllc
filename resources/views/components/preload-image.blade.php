{{--
    Preload a page's LCP image so it is requested straight from the <head>.
    Must use the same path and sizes as the <x-picture priority> it pairs
    with: both read WebpImage::responsive(), so srcset/src are identical and
    the browser downloads the image once. Pushed to the 'preload' stack in
    layouts/guest (first thing in <head>).
--}}
@props(['path' => null, 'sizes' => '100vw'])

@if ($path)
    @push('preload')
        @if ($set = \App\Support\WebpImage::responsive($path))
            <link rel="preload" as="image" href="{{ $set['src'] }}" imagesrcset="{{ $set['srcset'] }}" imagesizes="{{ $sizes }}" fetchpriority="high">
        @else
            <link rel="preload" as="image" href="{{ asset('storage/' . $path) }}" fetchpriority="high">
        @endif
    @endpush
@endif
