{{--
    Design-system button. Renders <a> when href is given, otherwise <button>.

    <x-button>Order Now</x-button>                          primary, 48px, full width on mobile
    <x-button variant="secondary" :block="false">…</x-button>
    <x-button href="/shop" icon="arrow-right" icon-position="end">Shop</x-button>
    <x-button :loading="true">Saving</x-button>              spinner, keeps width, not clickable

    variant: primary | secondary | soft | ghost | whatsapp
    size:    sm (40px) | md (48px) | lg (56px)
    block:   true = w-full below sm, auto from sm up (default true)
    States: hover, active (scale .97), focus-visible ring, loading, disabled.
--}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'submit',
    'block' => true,
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'start',
])

@php
    $classes = implode(' ', array_filter([
        'btn',
        'btn-' . $variant,
        $size === 'sm' ? 'btn-sm' : ($size === 'lg' ? 'btn-lg' : null),
        $block ? 'w-full sm:w-auto' : null,
    ]));
    $iconSize = $size === 'sm' ? 'size-4' : 'size-5';
@endphp

@if ($href)
    <a href="{{ $disabled ? '#' : $href }}"
       {{ $attributes->class($classes) }}
       @if ($disabled) aria-disabled="true" tabindex="-1" @endif
       @if ($loading) data-loading aria-busy="true" @endif>
        @if ($icon && $iconPosition === 'start') <x-shop.icon :name="$icon" :class="$iconSize" /> @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'end') <x-shop.icon :name="$icon" :class="$iconSize" /> @endif
    </a>
@else
    <button type="{{ $type }}"
            {{ $attributes->class($classes) }}
            @disabled($disabled)
            @if ($loading) data-loading aria-busy="true" @endif>
        @if ($icon && $iconPosition === 'start') <x-shop.icon :name="$icon" :class="$iconSize" /> @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'end') <x-shop.icon :name="$icon" :class="$iconSize" /> @endif
    </button>
@endif
