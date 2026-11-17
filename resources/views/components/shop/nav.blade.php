@php
    // Only categories with at least one product — see Category::navItems() / megaMenu().
    $menu = \App\Models\Category::megaMenu();
    $current = request()->url();
@endphp

<nav class="border-b border-line bg-white" aria-label="Categories">
    {{-- Mobile: snap-scrolling chips with icons --}}
    <ul class="container-shop flex snap-x snap-mandatory scroll-px-4 gap-2 overflow-x-auto py-2.5 sm:scroll-px-6 [scrollbar-width:none] md:hidden [&::-webkit-scrollbar]:hidden">
        @foreach ($menu as $c)
            @php $href = route('shop.category', $c['slug']); $active = $current === $href; @endphp
            <li class="shrink-0 snap-start">
                <a href="{{ $href }}" @if ($active) aria-current="page" @endif
                   class="flex min-h-10 items-center gap-1.5 rounded-full border px-3.5 text-[13px] font-bold transition active:scale-95 {{ $active ? 'border-brand-600 bg-brand-600 text-white' : 'border-transparent bg-' . $c['tone'] . '-100 text-ink' }}">
                    <x-shop.icon :name="$c['icon']" class="size-4 {{ $active ? '' : 'text-' . $c['tone'] . '-600' }}" />
                    {{ $c['label'] ?? $c['name'] }}
                </a>
            </li>
        @endforeach
        <li class="shrink-0 snap-start">
            <a href="{{ route('shop') }}" class="flex min-h-10 items-center gap-1.5 rounded-full border border-line bg-white px-3.5 text-[13px] font-bold text-brand-600">
                Shop all <x-shop.icon name="arrow-right" class="size-4" />
            </a>
        </li>
    </ul>

    {{-- Desktop: category row with mega-menu panels (hover or keyboard focus) --}}
    <div class="container-shop relative hidden md:block">
        <ul class="flex items-center gap-1 text-sm font-bold">
            <li>
                @php $home = request()->routeIs('index'); @endphp
                <a href="{{ route('index') }}" @if ($home) aria-current="page" @endif
                   class="flex items-center gap-1.5 border-b-2 px-3 py-3 {{ $home ? 'border-brand-600 text-brand-600' : 'border-transparent text-ink hover:text-brand-600' }}">
                    <x-shop.icon name="home" class="size-4" /> Home
                </a>
            </li>

            @foreach ($menu as $c)
                @php $href = route('shop.category', $c['slug']); $active = $current === $href; @endphp
                <li class="group/mega">
                    <a href="{{ $href }}" @if ($active) aria-current="page" @endif aria-haspopup="true"
                       class="flex items-center gap-1.5 border-b-2 px-3 py-3 {{ $active ? 'border-brand-600 text-brand-600' : 'border-transparent text-ink group-hover/mega:border-brand-500 group-hover/mega:text-brand-600' }}">
                        <x-shop.icon :name="$c['icon']" class="size-4" /> {{ $c['label'] ?? $c['name'] }}
                        <x-shop.icon name="chevron-down" class="size-3.5 text-muted transition-transform group-hover/mega:rotate-180" />
                    </a>

                    {{-- Panel: visibility (not display) so it can fade; delay avoids flicker when the pointer crosses items. --}}
                    <div class="invisible absolute inset-x-4 top-full z-40 translate-y-1 opacity-0 transition-[opacity,transform,visibility] delay-100 duration-200 ease-soft group-focus-within/mega:visible group-focus-within/mega:translate-y-0 group-focus-within/mega:opacity-100 group-hover/mega:visible group-hover/mega:translate-y-0 group-hover/mega:opacity-100 sm:inset-x-6">
                        <div class="mt-px grid grid-cols-[15rem_1fr] gap-6 rounded-b-card border border-t-0 border-line bg-white p-5 shadow-lift">
                            <a href="{{ $href }}" class="group/cat flex flex-col overflow-hidden rounded-card bg-{{ $c['tone'] }}-100 p-4">
                                @if ($c['image'])
                                    <img src="{{ $c['image'] }}" alt="" width="200" height="200" loading="lazy"
                                         class="mx-auto size-36 object-contain mix-blend-multiply transition-transform duration-300 group-hover/cat:scale-105">
                                @endif
                                <span class="mt-3 font-display text-lg font-semibold text-{{ $c['tone'] }}-600">{{ $c['label'] ?? $c['name'] }}</span>
                                <span class="mt-1 inline-flex items-center gap-1 text-xs font-extrabold text-ink">
                                    View all {{ $c['count'] }} {{ \Illuminate\Support\Str::plural('product', $c['count']) }}
                                    <x-shop.icon name="arrow-right" class="size-3.5" />
                                </span>
                            </a>
                            <ul class="grid grid-cols-4 gap-4">
                                @foreach ($c['products'] as $p)
                                    <li>
                                        <a href="{{ $p['url'] }}" class="group/p block">
                                            <span class="block overflow-hidden rounded-img bg-frame">
                                                <img src="{{ $p['image'] }}" alt="" width="200" height="200" loading="lazy"
                                                     class="aspect-square w-full object-contain p-2 mix-blend-multiply transition-transform duration-300 group-hover/p:scale-105">
                                            </span>
                                            <span class="mt-2 line-clamp-2 text-[13px] font-bold leading-snug text-ink group-hover/p:text-brand-600">{{ $p['title'] }}</span>
                                            <span class="mt-1 flex items-baseline gap-1.5 text-[13px]">
                                                <span class="font-extrabold {{ $p['was'] ? 'text-coral-600' : 'text-ink' }}">{{ money($p['price']) }}</span>
                                                @if ($p['was']) <span class="text-[11px] text-muted line-through">{{ money($p['was']) }}</span> @endif
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
            @endforeach

            <li class="ml-auto"><a href="{{ route('about') }}" class="block px-3 py-3 text-ink hover:text-brand-600">About</a></li>
            <li><a href="{{ route('contacts.index') }}" class="block px-3 py-3 text-ink hover:text-brand-600">Contact</a></li>
        </ul>
    </div>
</nav>
