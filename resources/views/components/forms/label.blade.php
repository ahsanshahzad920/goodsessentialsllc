@props(['value'])

<label {{ $attributes->merge(['class' => 'fw-bold mb-1 form-label']) }}>
    {{ $value ?? $slot }}
</label>
