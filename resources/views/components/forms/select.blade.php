<select {!! $attributes->merge(['class' => 'form-select ' . ($errors->has($name) ? ' is-invalid' : '')]) !!}>
    <option selected disabled>{{ __('Select one') }}</option>
    {{ $slot }}
</select>
