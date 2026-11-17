@props(['title', 'text', 'image' => null, 'tone' => 'lavender', 'href' => '#', 'icon' => 'tag'])
<a href="{{ $href }}" class="group flex items-center justify-between gap-3 rounded-3xl bg-{{ $tone }}-100 p-4 sm:p-5">
    <div class="min-w-0">
        <h3 class="text-lg font-semibold leading-tight text-{{ $tone }}-600">{{ $title }}</h3>
        <p class="mt-1.5 text-xs text-muted">{{ $text }}</p>
        <span class="mt-4 inline-flex items-center gap-1 text-xs font-extrabold">
            Shop Now <x-shop.icon name="arrow-right" class="size-3.5 transition group-hover:translate-x-0.5" />
        </span>
    </div>
    @if ($image)
        {{-- Real product photo on a soft frame; contain so packshots are never cropped. --}}
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy" width="96" height="96"
             class="size-24 shrink-0 rounded-2xl bg-[#FFF7F2] object-contain p-1.5 transition group-hover:scale-105">
    @else
        <span class="grid size-24 shrink-0 place-items-center rounded-2xl bg-white/70 text-{{ $tone }}-600" aria-hidden="true">
            <x-shop.icon :name="$icon" class="size-9" />
        </span>
    @endif
</a>
