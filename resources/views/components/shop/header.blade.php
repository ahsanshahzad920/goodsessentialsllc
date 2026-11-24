@props(['cartCount' => 0])

@php
    $isAdmin = auth()->check() && auth()->user()->hasRole('admin');
    $bounce = session('cart_added') ? 'animate-cart-bounce' : '';
    $iconBtn = 'relative size-11 shrink-0 place-items-center rounded-full text-ink transition hover:bg-brand-50 active:scale-95';
@endphp

{{--
    Sticky header. The outer wrapper (layouts/guest) keeps a FIXED height, and
    only this bar shrinks (72px -> 56px desktop, 64px -> 56px mobile) when the
    page scrolls, so nothing below it ever shifts (no CLS).
    Mobile: menu · logo (centred) · search · cart.  Desktop: logo · search · wishlist · account · cart.
--}}
<header data-site-header
        class="group/hdr flex h-16 items-center border-b border-line bg-white/95 backdrop-blur transition-[height,box-shadow] duration-300 ease-soft md:h-[72px] [.is-stuck_&]:h-14 [.is-stuck_&]:shadow-soft">
    <div class="container-shop flex w-full items-center gap-1 sm:gap-3 md:gap-6">
        <button type="button" data-sheet-open="menu-sheet" class="{{ $iconBtn }} grid md:hidden" aria-label="Open menu">
            <x-shop.icon name="menu" class="size-6" />
        </button>

        <div class="flex flex-1 justify-center md:flex-none md:justify-start">
            <x-shop.logo img-class="h-10 w-auto transition-[height] duration-300 ease-soft md:h-12 [.is-stuck_&]:h-9" />
        </div>

        {{-- Desktop search with instant results --}}
        <div class="relative mx-auto hidden w-full max-w-xl md:block" data-search>
            <form action="{{ route('shop') }}" method="GET" role="search"
                  class="flex items-center rounded-full border border-line bg-white py-1 pl-5 pr-1 shadow-sm transition focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-100">
                <label for="site-search" class="sr-only">Search products</label>
                <input id="site-search" name="q" type="search" value="{{ request('q') }}" placeholder="Search swaddles, caps, toys…"
                       autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="site-search-results"
                       data-search-input
                       class="w-full bg-transparent text-sm text-ink placeholder:text-muted focus:outline-none">
                <button type="submit" class="grid size-10 shrink-0 place-items-center rounded-full bg-coral-600 text-white transition hover:bg-coral-700 active:scale-95" aria-label="Search">
                    <x-shop.icon name="search" class="size-4" />
                </button>
            </form>
            <div id="site-search-results" data-search-panel hidden
                 class="absolute inset-x-0 top-full z-50 mt-2 max-h-[70vh] overflow-y-auto rounded-card border border-line bg-white p-2 shadow-lift"></div>
        </div>

        <div class="flex items-center gap-0.5 sm:gap-2">
            <button type="button" data-search-open class="{{ $iconBtn }} grid md:hidden" aria-label="Search products">
                <x-shop.icon name="search" class="size-6" />
            </button>

            <a href="{{ route('wishlist') }}" class="{{ $iconBtn }} hidden md:grid" aria-label="Wishlist">
                <x-shop.icon name="heart" class="size-6" />
            </a>

            @auth
                <details class="group relative hidden md:block">
                    <summary class="{{ $iconBtn }} grid cursor-pointer list-none [&::-webkit-details-marker]:hidden" aria-label="Open account menu">
                        <x-shop.icon name="user" class="size-6" />
                    </summary>
                    <ul class="absolute right-0 top-full z-50 mt-2 w-48 rounded-xl border border-line bg-white py-2 shadow-lift">
                        @if ($isAdmin)
                            <li><a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm font-medium text-ink hover:bg-brand-50">Admin panel</a></li>
                            <li><a href="{{ route('profile') }}" class="block px-4 py-2.5 text-sm font-medium text-ink hover:bg-brand-50">Profile</a></li>
                        @else
                            <li><a href="{{ route('account.orders.index') }}" class="block px-4 py-2.5 text-sm font-medium text-ink hover:bg-brand-50">My Orders</a></li>
                            <li><a href="{{ route('user.profile.show') }}" class="block px-4 py-2.5 text-sm font-medium text-ink hover:bg-brand-50">Profile</a></li>
                        @endif
                        <li class="mt-1 border-t border-line pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 text-left text-sm font-medium text-ink hover:bg-brand-50">Logout</button>
                            </form>
                        </li>
                    </ul>
                </details>
            @else
                <a href="{{ route('login') }}" class="{{ $iconBtn }} hidden md:grid" aria-label="Log in">
                    <x-shop.icon name="user" class="size-6" />
                </a>
            @endauth

            <a href="{{ route('cart') }}" class="{{ $iconBtn }} grid" aria-label="Cart, {{ $cartCount }} {{ \Illuminate\Support\Str::plural('item', $cartCount) }}">
                <x-shop.icon name="cart" class="size-6" />
                <span data-cart-count
                      class="{{ $bounce }} absolute right-0.5 top-0.5 {{ $cartCount ? 'grid' : 'hidden' }} min-w-[1.15rem] place-items-center rounded-full bg-coral-600 px-1 text-[10px] font-extrabold leading-[1.15rem] text-white"
                      aria-hidden="true">{{ $cartCount }}</span>
            </a>
        </div>
    </div>
</header>

{{-- Close the account dropdown when clicking elsewhere or pressing Escape --}}
<script>
    document.addEventListener('click', function (e) {
        document.querySelectorAll('header details[open]').forEach(function (d) {
            if (!d.contains(e.target)) d.removeAttribute('open');
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('header details[open]').forEach(function (d) { d.removeAttribute('open'); });
        }
    });
</script>
