{{--
    <x-price :amount="5850" :original="9000" />                  Rs. 5,850  ~~Rs. 9,000~~  [Save Rs. 3,150]
    <x-price :amount="$p" size="lg" :show-save="false" />

    amount   = what the customer pays; original = pre-discount price (optional).
    The strike-through and "Save" pill only render when original > amount.
--}}
@props(['amount', 'original' => null, 'size' => 'md', 'showSave' => true])

@php
    $toNumber = fn ($v) => (float) str_replace(',', '', (string) $v);
    $now = $toNumber($amount);
    $was = $original !== null ? $toNumber($original) : 0;
    $onSale = $was > 0 && $now > 0 && $now < $was;

    $main = ['sm' => 'text-[15px]', 'md' => 'text-lg', 'lg' => 'text-2xl sm:text-3xl'][$size] ?? 'text-lg';
    $old  = ['sm' => 'text-[11px]', 'md' => 'text-sm', 'lg' => 'text-base'][$size] ?? 'text-sm';
@endphp

<p {{ $attributes->class('flex flex-wrap items-baseline gap-x-2 gap-y-1') }}>
    <span class="{{ $main }} font-extrabold tabular-nums {{ $onSale ? 'text-coral-600' : 'text-ink' }}">
        <span class="sr-only">{{ $onSale ? 'Sale price' : 'Price' }}:</span>{{ money($now) }}
    </span>
    @if ($onSale)
        <span class="{{ $old }} text-muted line-through tabular-nums">
            <span class="sr-only">Original price:</span>{{ money($was) }}
        </span>
        @if ($showSave)
            <x-badge tone="success" :size="$size === 'lg' ? 'md' : 'xs'">Save {{ money($was - $now) }}</x-badge>
        @endif
    @endif
</p>
