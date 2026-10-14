@props(['cartCount' => 0])

<header class="border-b border-line bg-white">
    <div class="container-shop flex items-center gap-3 py-3 sm:gap-6">
        <button type="button" id="menu-toggle" aria-controls="mobile-menu" aria-expanded="false"
                class="rounded-full p-2 text-ink hover:bg-brand-50" aria-label="Open menu">
            <x-shop.icon name="menu" class="size-6" />
        </button>

        <x-shop.logo />

        {{-- Search (desktop). Your shop controller must read ?q= to filter. --}}
        <form action="{{ route('shop') }}" method="GET" role="search"
              class="mx-auto hidden w-full max-w-xl items-center rounded-full border border-line bg-white py-1 pl-5 pr-1 shadow-sm focus-within:border-brand-500 md:flex">
            <label for="site-search" class="sr-only">Search products</label>
            <input id="site-search" name="q" type="search" value="{{ request('q') }}" placeholder="Search for products..."
                   class="w-full bg-transparent text-sm text-ink placeholder:text-muted focus:outline-none">
            <button type="submit" class="grid size-9 shrink-0 place-items-center rounded-full bg-brand-500 text-white hover:bg-brand-600" aria-label="Search">
                <x-shop.icon name="search" class="size-4" />
            </button>
        </form>

        <div class="ml-auto flex items-center gap-5 md:ml-0">
            <a href="{{ route('wishlist') }}" class="flex flex-col items-center text-[11px] font-semibold text-muted hover:text-ink">
                <x-shop.icon name="heart" class="size-6 text-ink" />
                <span class="hidden sm:inline">Wishlist</span>
            </a>
            @auth
                <div class="relative">
                    <details class="group">
                        <summary
                            class="flex cursor-pointer list-none flex-col items-center text-[11px] font-semibold text-muted hover:text-ink [&::-webkit-details-marker]:hidden"
                            aria-label="Open account menu">
                            <x-shop.icon name="user" class="size-6 text-ink" />
                            <span class="hidden sm:inline">My Account</span>
                        </summary>

                        <ul class="absolute right-0 top-full z-50 mt-2 w-44 rounded-xl border border-line bg-white py-2 shadow-lg">
                            <li>
                                <a href="{{ route('profile') }}"
                                class="block px-4 py-2.5 text-sm font-medium text-ink hover:bg-brand-50">
                                    Profile
                                </a>
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full px-4 py-2.5 text-left text-sm font-medium text-ink hover:bg-brand-50">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </details>
                </div>
            @else
                <a href="{{ route('login') }}"
                class="flex flex-col items-center text-[11px] font-semibold text-muted hover:text-ink">
                    <x-shop.icon name="user" class="size-6 text-ink" />
                    <span class="hidden sm:inline">Account</span>
                </a>
            @endauth
            <a href="{{ route('cart') }}" class="relative flex flex-col items-center text-[11px] font-semibold text-muted hover:text-ink">
                <x-shop.icon name="cart" class="size-6 text-ink" />
                <span class="absolute -right-2 -top-1.5 grid size-4 place-items-center rounded-full bg-blush-600 text-[10px] font-bold text-white">{{ $cartCount }}</span>
                <span class="hidden sm:inline">Cart</span>
            </a>
        </div>
    </div>

    {{-- Search (mobile) --}}
    <form action="{{ route('shop') }}" method="GET" role="search" class="container-shop pb-3 md:hidden">
        <div class="flex items-center rounded-full border border-line py-1 pl-4 pr-1">
            <input name="q" type="search" value="{{ request('q') }}" placeholder="Search products..." aria-label="Search products"
                   class="w-full bg-transparent text-sm placeholder:text-muted focus:outline-none">
            <button class="grid size-8 place-items-center rounded-full bg-brand-500 text-white" aria-label="Search"><x-shop.icon name="search" class="size-4" /></button>
        </div>
    </form>

    {{-- Hamburger panel: keeps your old Home / About / Shop / Contact links reachable --}}
    <nav id="mobile-menu" hidden class="border-t border-line bg-white" aria-label="Site menu">
        <ul class="container-shop grid gap-1 py-3 text-sm font-bold sm:grid-cols-4">
            <li>
                
            </li>
        </ul>
    </nav>
</header>
