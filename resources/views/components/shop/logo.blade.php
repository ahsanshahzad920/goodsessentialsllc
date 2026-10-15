@php
    $image = config('shop.logo_image');
    $name  = config('shop.name', config('app.name'));
    $tones = ['text-lavender-600', 'text-blush-600', 'text-mint-600', 'text-sun-600', 'text-sky-600', 'text-peach-600', 'text-leaf-600'];
@endphp

<a href="{{ route('index') }}" {{ $attributes->merge(['class' => 'inline-flex flex-col leading-none']) }} aria-label="{{ $name }} home">
    @if ($image)
        <img src="{{ asset($image) }}" alt="{{ $name }}" class="h-12 w-auto">
    @else
        <span class="font-display text-3xl font-bold tracking-wide">
            @foreach (str_split(strtoupper($name)) as $i => $char)<span class="{{ $tones[$i % count($tones)] }}">{{ $char }}</span>@endforeach
        </span>
        <span class="mt-0.5 text-[10px] font-bold text-brand-500">{{ config('shop.tagline') }}</span>
    @endif
</a>
