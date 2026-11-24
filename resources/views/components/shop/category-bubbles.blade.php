{{--
    "Shop by category" as story-style bubbles: real product photos (category
    image or its best seller) inside soft pastel rings. Snap-scrolls on phones.
--}}
@props(['categories'])

@if ($categories->isNotEmpty())
<section aria-labelledby="cat-bubbles-title" class="space-y-4">
    <x-section-header id="cat-bubbles-title" title="Shop by Category" link-text="Shop all" :href="route('shop')" />
    <ul class="-mx-4 flex snap-x snap-mandatory scroll-px-4 gap-3 overflow-x-auto px-4 pb-1 [scrollbar-width:none] sm:mx-0 sm:flex-wrap sm:justify-center sm:gap-6 sm:overflow-visible sm:px-0 [&::-webkit-scrollbar]:hidden">
        @foreach ($categories as $c)
            <li class="w-[5.75rem] shrink-0 snap-start sm:w-28">
                <a href="{{ route('shop.category', $c['slug']) }}" class="bubble-tap group flex flex-col items-center gap-2 text-center">
                    {{-- Gradient ring = pastel tone; inner white gap; photo --}}
                    <span class="rounded-full bg-gradient-to-tr from-{{ $c['tone'] }}-600/60 via-coral-500/50 to-butter p-[3px] transition-transform duration-300 ease-soft group-hover:scale-105 group-active:scale-95">
                        <span class="block rounded-full bg-white p-[3px]">
                            <span class="grid size-[4.75rem] place-items-center overflow-hidden rounded-full bg-{{ $c['tone'] }}-100 sm:size-24">
                                @if ($c['image'])
                                    <img src="{{ $c['image'] }}" alt="" width="96" height="96" loading="lazy" decoding="async"
                                         class="size-full object-contain p-1.5 mix-blend-multiply">
                                @else
                                    <x-shop.icon :name="$c['icon']" class="size-8 text-{{ $c['tone'] }}-600" />
                                @endif
                            </span>
                        </span>
                    </span>
                    <span class="line-clamp-2 text-xs font-bold leading-tight text-ink sm:text-[13px]">{{ $c['label'] ?? $c['name'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</section>
@endif
