@props(['action' => '', 'method' => 'POST'])

<form action="{{ $action }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
    {{ $attributes->merge(['class' => '']) }} enctype="multipart/form-data">
    @csrf

    @unless (in_array($method, ['GET', 'POST']))
        @method($method)
    @endunless

    {{ $slot }}
</form>
