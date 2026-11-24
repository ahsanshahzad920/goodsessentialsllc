@props(['cartCount' => 0])

{{--
    Mobile bottom navigation (hidden from md up). Hides while scrolling down,
    comes back on scroll up (transform only). The layout reserves its height
    with bottom padding so it never covers the footer.
--}}
@php
    $item = 'flex min-h-11 flex-1 flex-col items-center justify-center gap-0.5 text-[11px] font-bold transition active:scale-95';
    $on = 'text-coral-600';
    $off = 'text-muted';
@endphp

<nav id="bottom-nav" aria-label="Quick navigation"
     class="fixed inset-x-0 bottom-0 z-40 border-t border-line bg-white/95 pb-[env(safe-area-inset-bottom)] shadow-[0_-8px_24px_-16px_rgb(31_27_58/.25)] backdrop-blur transition-transform duration-300 ease-soft md:hidden">
    <ul class="flex h-16 items-stretch px-1">
        <li class="flex flex-1">
            <a href="{{ route('index') }}" class="{{ $item }} {{ request()->routeIs('index') ? $on : $off }}" @if (request()->routeIs('index')) aria-current="page" @endif>
                <x-shop.icon name="home" class="size-6" /> Home
            </a>
        </li>
        <li class="flex flex-1">
            <button type="button" data-sheet-open="menu-sheet" class="{{ $item }} {{ request()->routeIs('shop', 'shop.category') ? $on : $off }}">
                <x-shop.icon name="grid" class="size-6" /> Categories
            </button>
        </li>
        <li class="flex flex-1">
            <button type="button" data-search-open class="{{ $item }} {{ $off }}">
                <x-shop.icon name="search" class="size-6" /> Search
            </button>
        </li>
        <li class="flex flex-1">
            <a href="{{ route('wishlist') }}" class="{{ $item }} {{ request()->routeIs('wishlist') ? $on : $off }}" @if (request()->routeIs('wishlist')) aria-current="page" @endif>
                <x-shop.icon name="heart" class="size-6" /> Wishlist
            </a>
        </li>
        <li class="flex flex-1">
            <a href="{{ route('cart') }}" class="{{ $item }} {{ request()->routeIs('cart') ? $on : $off }}" @if (request()->routeIs('cart')) aria-current="page" @endif>
                <span class="relative">
                    <x-shop.icon name="cart" class="size-6" />
                    <span data-cart-count aria-hidden="true"
                          class="{{ session('cart_added') ? 'animate-cart-bounce' : '' }} absolute -right-2.5 -top-1.5 {{ $cartCount ? 'grid' : 'hidden' }} min-w-[1.15rem] place-items-center rounded-full bg-coral-600 px-1 text-[10px] font-extrabold leading-[1.15rem] text-white">{{ $cartCount }}</span>
                </span>
                Cart<span class="sr-only">, {{ $cartCount }} {{ \Illuminate\Support\Str::plural('item', $cartCount) }}</span>
            </a>
        </li>
    </ul>
</nav>
