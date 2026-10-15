@props(['title', 'linkText' => null, 'href' => '#', 'heart' => false])

<div class="flex items-center justify-between">
    <h2 class="flex items-center gap-2 text-2xl font-semibold">
        {{ $title }}
        @if ($heart) <x-shop.icon name="heart" class="size-5 text-blush-600" /> @endif
    </h2>
    @if ($linkText)
        <a href="{{ $href }}" class="inline-flex items-center gap-1 text-sm font-bold text-brand-500 hover:text-brand-600">
            {{ $linkText }} <x-shop.icon name="arrow-right" class="size-4" />
        </a>
    @endif
</div>
