@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'placeholder' => '', 'autocomplete' => null])

@php
    $id  = 'f-' . $name;
    $has = $errors->has($name);
@endphp

<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="mb-1.5 block text-sm font-semibold">
        {{ $label }} @if ($required)<span class="text-blush-600">*</span>@endif
    </label>
    <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}"
           value="{{ $type === 'password' ? '' : old($name, $value) }}"
           placeholder="{{ $placeholder }}"
           @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
           @required($required)
           @if ($has) aria-invalid="true" @endif
           class="block w-full rounded-xl border bg-white px-4 py-3 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/15 {{ $has ? 'border-blush-600' : 'border-line' }}">
    @error($name)
        <p class="mt-1 text-xs font-semibold text-blush-600">{{ $message }}</p>
    @enderror
</div>
