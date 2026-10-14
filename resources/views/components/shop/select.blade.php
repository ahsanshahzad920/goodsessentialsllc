@props(['name', 'label', 'id' => null])

@php $id = $id ?? 'f-' . $name; @endphp

<div>
    <label for="{{ $id }}" class="mb-1.5 block text-xs font-bold">{{ $label }}</label>
    <div class="relative">
        <select id="{{ $id }}" name="{{ $name }}"
            {{ $attributes->merge(['class' => 'w-full appearance-none rounded-full border border-line bg-white py-2.5 pl-4 pr-9 text-xs text-ink focus:border-brand-500 focus:outline-none']) }}>
            {{ $slot }}
        </select>
        <x-shop.icon name="chevron-down" class="pointer-events-none absolute right-3 top-1/2 size-4 -translate-y-1/2 text-muted" />
    </div>
</div>
