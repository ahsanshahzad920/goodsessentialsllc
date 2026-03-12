<textarea {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    rows="3">{!! $value ?? $slot !!}</textarea>
