{{--
    <x-rating-stars :rating="4.6" :count="12" />
    Renders nothing without a real rating — never shows placeholder stars.
--}}
@props(['rating' => null, 'count' => null, 'size' => 'sm', 'showValue' => false])

@if ($rating)
    @php
        $r = max(0, min(5, (float) $rating));
        $star = ['xs' => 'size-3', 'sm' => 'size-3.5', 'md' => 'size-4', 'lg' => 'size-5'][$size] ?? 'size-3.5';
    @endphp
    <span {{ $attributes->class('inline-flex items-center gap-1') }}>
        <span class="flex" role="img" aria-label="Rated {{ number_format($r, 1) }} out of 5{{ $count ? ', ' . $count . ' ' . \Illuminate\Support\Str::plural('review', $count) : '' }}">
            @for ($i = 1; $i <= 5; $i++)
                <x-shop.icon name="star" class="{{ $star }} {{ $i <= round($r) ? 'fill-star text-star' : 'text-line' }}" />
            @endfor
        </span>
        @if ($showValue)
            <span class="text-xs font-bold text-ink" aria-hidden="true">{{ number_format($r, 1) }}</span>
        @endif
        @if ($count)
            <span class="text-xs text-muted" aria-hidden="true">({{ $count }})</span>
        @endif
    </span>
@endif
