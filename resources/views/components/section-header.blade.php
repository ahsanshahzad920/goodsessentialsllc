{{--
    <x-section-header title="Best Sellers" eyebrow="Loved by moms" link-text="View all" :href="route('shop')" />
    <x-section-header title="What Our Customers Say" align="center" subtitle="…" />
--}}
@props([
    'title',
    'eyebrow' => null,
    'subtitle' => null,
    'linkText' => null,
    'href' => '#',
    'align' => 'left',
    'heart' => false,
    'as' => 'h2',
    'id' => null,
])

@php $center = $align === 'center'; @endphp

<div {{ $attributes->class([
    'flex gap-3',
    'flex-col items-center text-center' => $center,
    'items-end justify-between' => ! $center,
]) }}>
    <div class="min-w-0">
        @if ($eyebrow)
            <p class="text-xs font-extrabold uppercase tracking-wider text-coral-600">{{ $eyebrow }}</p>
        @endif
        <{{ $as }} @if ($id) id="{{ $id }}" @endif class="{{ $eyebrow ? 'mt-1' : '' }} flex items-center gap-2 text-2xl font-semibold text-ink sm:text-[1.75rem] {{ $center ? 'justify-center' : '' }}">
            {{ $title }}
            @if ($heart) <x-shop.icon name="heart" class="size-5 fill-current text-coral-500" /> @endif
        </{{ $as }}>
        @if ($subtitle)
            <p class="mt-2 text-sm text-muted">{{ $subtitle }}</p>
        @endif
    </div>
    @if ($linkText)
        <a href="{{ $href }}" class="inline-flex min-h-11 shrink-0 items-center gap-1 text-sm font-extrabold text-brand-600 hover:text-brand-700">
            {{ $linkText }} <x-shop.icon name="arrow-right" class="size-4" />
        </a>
    @endif
</div>
