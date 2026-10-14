@php
$configuredCategories = collect(config('shop.categories', []))
        ->keyBy(fn ($category) => strtolower($category['name']));
    $categories = \App\Models\Category::orderBy('name')
            ->get()
            ->map(function ($category) use ($configuredCategories) {
                $settings = $configuredCategories->get(strtolower($category->name), []);

                return [
                    'name' => $category->name,
                    'label' => $settings['label'] ?? $category->name,
                    'icon' => $settings['icon'] ?? 'tag',
                    'tone' => $settings['tone'] ?? 'lavender',
                ];
            });
@endphp
<footer class="mt-12 border-t border-line bg-white">
    <div class="container-shop grid gap-8 py-10 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <x-shop.logo />
            <p class="mt-3 max-w-xs text-sm text-muted">Quality everyday essentials for babies, kids and home.</p>
        </div>

        <div>
            <h3 class="text-base font-semibold">Shop</h3>
            <ul class="mt-3 space-y-2 text-sm text-muted">
                @foreach ($categories as $c)
                    <li><a href="{{ route('shop', $c['name']) }}" class="hover:text-brand-500">{{ $c['label'] ?? $c['name'] }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="text-base font-semibold">Help</h3>
            <ul class="mt-3 space-y-2 text-sm text-muted">
                <li><a href="{{ route('about') }}" class="hover:text-brand-500">About us</a></li>
                <li><a href="{{ route('contacts.index') }}" class="hover:text-brand-500">Contact us</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-base font-semibold">My account</h3>
            <ul class="mt-3 space-y-2 text-sm text-muted">
                <li><a href="{{ route('cart') }}" class="hover:text-brand-500">Cart</a></li>
                <li><a href="{{ route('wishlist') }}" class="hover:text-brand-500">Wishlist</a></li>
                <li>
                    @auth <a href="{{ route('dashboard') }}" class="hover:text-brand-500">Dashboard</a>
                    @else <a href="{{ route('login') }}" class="hover:text-brand-500">Log in</a> @endauth
                </li>
            </ul>
        </div>
    </div>
    <p class="border-t border-line py-4 text-center text-xs text-muted">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
</footer>
