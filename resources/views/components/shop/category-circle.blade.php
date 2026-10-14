@props(['label', 'icon', 'tone' => 'lavender', 'href' => '#'])

<a href="{{ $href }}" class="group flex flex-col items-center gap-2.5 text-center">
    <span class="grid size-16 place-items-center rounded-full bg-{{ $tone }}-100 text-{{ $tone }}-600 transition group-hover:scale-105 sm:size-[4.5rem]">
        <x-shop.icon :name="$icon" class="size-7" />
    </span>
    <span class="text-xs font-bold sm:text-[13px]">{{ $label }}</span>
</a>
