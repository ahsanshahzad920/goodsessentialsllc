{{--
    The four store promises. Horizontal snap-scroll on phones, 4-up grid from md.
    <x-trust-strip />   <x-trust-strip variant="solid" />
--}}
@props(['variant' => 'soft'])

@php
    $items = [
        ['banknote',   'Cash on Delivery',   'Pay when it arrives'],
        ['package',    'Open Parcel Check',  'Check before you pay'],
        ['truck',      '1–3 Day Delivery',   'All over Pakistan'],
        ['rotate-ccw', '14-Day Returns',     'Easy & hassle-free'],
    ];
    $solid = $variant === 'solid';
@endphp

<section aria-label="Our promises" {{ $attributes->class(['rounded-card', 'bg-brand-600 text-white' => $solid, 'bg-white shadow-soft' => ! $solid]) }}>
    <ul class="flex snap-x snap-mandatory scroll-px-3 gap-2 overflow-x-auto px-3 py-3 [scrollbar-width:none] md:grid md:grid-cols-4 md:gap-0 md:overflow-visible md:px-2 [&::-webkit-scrollbar]:hidden">
        @foreach ($items as $i => [$icon, $title, $text])
            <li class="flex min-w-[11.5rem] shrink-0 snap-start items-center gap-3 rounded-2xl px-3 py-2 md:min-w-0 md:rounded-none {{ $i > 0 ? ($solid ? 'md:border-l md:border-white/25' : 'md:border-l md:border-line') : '' }}">
                <span class="grid size-11 shrink-0 place-items-center rounded-full {{ $solid ? 'bg-white/15' : 'bg-coral-50 text-coral-600' }}">
                    <x-shop.icon :name="$icon" class="size-5" />
                </span>
                <span>
                    <span class="block text-sm font-extrabold leading-tight">{{ $title }}</span>
                    <span class="block text-xs {{ $solid ? 'text-white/85' : 'text-muted' }}">{{ $text }}</span>
                </span>
            </li>
        @endforeach
    </ul>
</section>
