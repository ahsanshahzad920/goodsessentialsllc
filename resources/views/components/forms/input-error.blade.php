@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-danger fw-bold ps-0 mt-1 text-start']) }} style="list-style-type: none;">
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
