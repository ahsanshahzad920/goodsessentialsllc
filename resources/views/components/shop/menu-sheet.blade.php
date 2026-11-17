{{-- Mobile menu: categories first (that's what shoppers want), then pages. Opened by the
     header hamburger and the bottom-nav "Categories" button. --}}
@php $menu = \App\Models\Category::navItems(); @endphp

<x-bottom-sheet id="menu-sheet" title="Shop by category" side="right">
    <ul class="grid grid-cols-2 gap-2.5">
        @foreach ($menu as $c)
            <li>
                <a href="{{ route('shop.category', $c['slug']) }}"
                   class="flex min-h-16 items-center gap-2.5 rounded-2xl bg-{{ $c['tone'] }}-100 p-3 text-sm font-bold text-ink transition active:scale-[.97]">
                    <span class="grid size-9 shrink-0 place-items-center rounded-full bg-white text-{{ $c['tone'] }}-600">
                        <x-shop.icon :name="$c['icon']" class="size-5" />
                    </span>
                    <span class="leading-tight">{{ $c['label'] ?? $c['name'] }}</span>
                </a>
            </li>
        @endforeach
        <li class="col-span-2">
            <x-button :href="route('shop')" variant="secondary" icon="arrow-right" icon-position="end" class="w-full sm:w-full">Shop all products</x-button>
        </li>
    </ul>

    <ul class="mt-6 divide-y divide-line border-y border-line text-[15px] font-bold">
        @foreach ([['Home', 'index', 'home'], ['About us', 'about', 'smile'], ['Contact', 'contacts.index', 'phone'], ['Blog', 'blog.index', 'book']] as [$label, $route, $icon])
            <li>
                <a href="{{ route($route) }}" class="flex min-h-12 items-center gap-3 py-2 text-ink">
                    <x-shop.icon :name="$icon" class="size-5 text-muted" /> {{ $label }}
                </a>
            </li>
        @endforeach
        <li>
            @auth
                <a href="{{ route('dashboard') }}" class="flex min-h-12 items-center gap-3 py-2 text-ink">
                    <x-shop.icon name="user" class="size-5 text-muted" /> My account & orders
                </a>
            @else
                <a href="{{ route('login') }}" class="flex min-h-12 items-center gap-3 py-2 text-ink">
                    <x-shop.icon name="user" class="size-5 text-muted" /> Log in / Sign up
                </a>
            @endauth
        </li>
    </ul>

    <a href="https://wa.me/{{ ltrim(config('shop.contact.whatsapp'), '+') }}" target="_blank" rel="noopener"
       class="btn btn-whatsapp mt-6 w-full">
        <x-shop.icon name="whatsapp" class="size-5" /> Chat with us on WhatsApp
    </a>
</x-bottom-sheet>
