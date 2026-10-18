@props(['name', 'hex' => null])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 text-xs text-muted']) }}>
    @if ($hex)
        <span class="inline-block size-3.5 rounded-full border border-black/10" style="background-color: {{ $hex }}"></span>
    @endif
    {{ $name }}
</span>
