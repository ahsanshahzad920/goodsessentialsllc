{{--
    <x-badge tone="sale">-35%</x-badge>
    tone: sale | new | success | info | warning | neutral
    All tones pass WCAG AA (4.5:1) for their text.
--}}
@props(['tone' => 'neutral', 'size' => 'sm'])

@php
    $tones = [
        'sale'    => 'bg-coral-600 text-white',
        'new'     => 'bg-success-600 text-white',
        'success' => 'bg-mint-100 text-mint-600',
        'info'    => 'bg-brand-100 text-brand-700',
        'warning' => 'bg-sun-100 text-sun-600',
        'neutral' => 'bg-white/95 text-ink ring-1 ring-line',
    ];
    $sizes = [
        'xs' => 'px-2 py-0.5 text-[10px]',
        'sm' => 'px-2.5 py-0.5 text-[11px]',
        'md' => 'px-3 py-1 text-xs',
    ];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1 rounded-full font-extrabold leading-5', $tones[$tone] ?? $tones['neutral'], $sizes[$size] ?? $sizes['sm']]) }}>{{ $slot }}</span>
