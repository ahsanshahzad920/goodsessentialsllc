@props(['value' => 'Submit'])

<button type="submit" {!! $attributes->merge(['class' => 'btn btn-black']) !!}>
    {{ __($value) }}
</button>
