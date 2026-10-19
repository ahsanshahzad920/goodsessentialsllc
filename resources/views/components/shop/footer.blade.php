@php
    // Only categories with at least one product — see Category::navItems().
    $categories = \App\Models\Category::navItems();
    $contact = config('shop.contact', []);
@endphp
<footer class="mt-12 border-t border-line bg-white">
    <div class="container-shop grid gap-8 py-10 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <x-shop.logo />
            <p class="mt-3 max-w-xs text-sm text-muted">Baby & mom essentials, delivered across Pakistan.</p>
            {{-- Same source as the Contact page and all policy pages (config/shop.php). --}}
            <ul class="mt-3 space-y-1 text-sm text-muted">
                @if (! empty($contact['phone']))
                    <li><a href="tel:{{ $contact['phone'] }}" class="hover:text-brand-500">{{ $contact['phone'] }}</a></li>
                @endif
                @if (! empty($contact['email']))
                    <li><a href="mailto:{{ $contact['email'] }}" class="hover:text-brand-500">{{ $contact['email'] }}</a></li>
                @endif
                @if ($address = config('shop.address'))
                    <li>{{ $address }}</li>
                @endif
            </ul>
        </div>

        <div>
            <h3 class="text-base font-semibold">Shop</h3>
            <ul class="mt-3 space-y-2 text-sm text-muted">
                @foreach ($categories as $c)
                    <li><a href="{{ route('shop.category', $c['slug']) }}" class="hover:text-brand-500">{{ $c['label'] ?? $c['name'] }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="text-base font-semibold">Help</h3>
            <ul class="mt-3 space-y-2 text-sm text-muted">
                <li><a href="{{ route('about') }}" class="hover:text-brand-500">About us</a></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-brand-500">Mom's Guide</a></li>
                <li><a href="{{ route('contacts.index') }}" class="hover:text-brand-500">Contact us</a></li>
                <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                <li><a href="{{ route('return-refund-policy') }}">Return & Refund Policy</a></li>
                <li><a href="{{ route('shipping-policy') }}">Shipping Policy</a></li>
                <li><a href="{{ route('terms-and-conditions') }}">Terms & Conditions</a></li>
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
