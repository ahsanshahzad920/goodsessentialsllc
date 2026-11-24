@php
    // Only categories with at least one product — see Category::navItems().
    $categories = \App\Models\Category::navItems();
    $contact = config('shop.contact', []);
    $social = array_filter(config('shop.social', []));
    $wa = ltrim($contact['whatsapp'] ?? '', '+');
@endphp
<footer class="mt-12 border-t border-line bg-white">
    {{-- WhatsApp updates opt-in (no discount promised: there is no coupon system yet) --}}
    <div class="bg-lavender-100">
        <div class="container-shop flex flex-col items-start gap-4 py-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="font-display text-xl font-semibold text-ink">New arrivals &amp; deals on WhatsApp</p>
                <p class="text-sm text-muted">One or two messages a week. Reply STOP anytime.</p>
            </div>
            <form action="{{ route('subscribe') }}" method="POST" data-subscribe class="flex w-full max-w-md gap-2">
                @csrf
                <label for="subscribe-phone" class="sr-only">Mobile number</label>
                <input id="subscribe-phone" name="phone" type="tel" inputmode="tel" required placeholder="03XX-XXXXXXX"
                       class="min-h-12 min-w-0 flex-1 rounded-full border border-line bg-white px-4 text-base focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-100">
                <button class="btn btn-whatsapp shrink-0 px-5"><x-shop.icon name="whatsapp" class="size-5" /> Join</button>
            </form>
        </div>
        @error('subscribe_phone') <p class="container-shop pb-4 text-xs font-bold text-coral-700">{{ $message }}</p> @enderror
    </div>

    <div class="container-shop grid gap-8 py-10 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <x-shop.logo />
            <p class="mt-3 max-w-xs text-sm text-muted">Baby &amp; mom essentials, delivered across Pakistan.</p>
            {{-- Same source as the Contact page and all policy pages (config/shop.php). --}}
            <ul class="mt-3 space-y-1.5 text-sm text-muted">
                @if ($wa)
                    <li><a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="inline-flex min-h-8 items-center gap-2 font-bold text-[#0e7a3f] hover:underline"><x-shop.icon name="whatsapp" class="size-4" /> WhatsApp {{ $contact['whatsapp'] }}</a></li>
                @endif
                @if (! empty($contact['phone']))
                    <li><a href="tel:{{ $contact['phone'] }}" class="inline-flex min-h-8 items-center gap-2 hover:text-brand-600"><x-shop.icon name="phone" class="size-4" /> {{ $contact['phone'] }}</a></li>
                @endif
                @if (! empty($contact['email']))
                    <li><a href="mailto:{{ $contact['email'] }}" class="inline-flex min-h-8 items-center gap-2 hover:text-brand-600"><x-shop.icon name="mail" class="size-4" /> {{ $contact['email'] }}</a></li>
                @endif
                @if ($address = config('shop.address'))
                    <li class="flex gap-2"><x-shop.icon name="map-pin" class="mt-0.5 size-4 shrink-0" /> {{ $address }}</li>
                @endif
            </ul>
            @if ($social)
                <ul class="mt-4 flex flex-wrap gap-2" aria-label="Follow us">
                    @foreach ($social as $network => $url)
                        <li><a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex min-h-10 items-center rounded-full bg-mist-100 px-4 text-xs font-extrabold text-ink hover:bg-brand-100">{{ \Illuminate\Support\Str::title($network) }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div>
            <h3 class="text-base font-semibold">Shop</h3>
            <ul class="mt-3 space-y-1 text-sm text-muted">
                @foreach ($categories as $c)
                    <li><a href="{{ route('shop.category', $c['slug']) }}" class="inline-flex min-h-8 items-center hover:text-brand-600">{{ $c['label'] ?? $c['name'] }}</a></li>
                @endforeach
                <li><a href="{{ route('gift-box') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Build a Gift Box</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-base font-semibold">Help</h3>
            <ul class="mt-3 space-y-1 text-sm text-muted">
                <li><a href="{{ route('about') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">About us</a></li>
                <li><a href="{{ route('blog.index') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Mom's Guide</a></li>
                <li><a href="{{ route('contacts.index') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Contact us</a></li>
                <li><a href="{{ route('privacy-policy') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Privacy Policy</a></li>
                <li><a href="{{ route('return-refund-policy') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Return &amp; Refund Policy</a></li>
                <li><a href="{{ route('shipping-policy') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Shipping Policy</a></li>
                <li><a href="{{ route('terms-and-conditions') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Terms &amp; Conditions</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-base font-semibold">My account</h3>
            <ul class="mt-3 space-y-1 text-sm text-muted">
                <li><a href="{{ route('cart') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Cart</a></li>
                <li><a href="{{ route('wishlist') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Wishlist</a></li>
                <li>
                    @auth <a href="{{ route('dashboard') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">My orders</a>
                    @else <a href="{{ route('login') }}" class="inline-flex min-h-8 items-center hover:text-brand-600">Log in</a> @endauth
                </li>
            </ul>
            <h3 class="mt-6 text-base font-semibold">We accept</h3>
            <ul class="mt-3 flex flex-wrap gap-2 text-[11px] font-extrabold text-ink">
                <li class="inline-flex items-center gap-1.5 rounded-lg border border-line px-2.5 py-1.5"><x-shop.icon name="banknote" class="size-4 text-mint-600" /> Cash on Delivery</li>
                <li class="inline-flex items-center gap-1.5 rounded-lg border border-line px-2.5 py-1.5"><x-shop.icon name="package" class="size-4 text-mint-600" /> Open parcel check</li>
                @if (count(\App\Payments\PaymentManager::methods()) > 1)
                    <li class="inline-flex items-center gap-1.5 rounded-lg border border-line px-2.5 py-1.5"><x-shop.icon name="credit-card" class="size-4 text-brand-600" /> Card</li>
                @endif
            </ul>
        </div>
    </div>
    <p class="border-t border-line py-4 text-center text-xs text-muted">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
</footer>

<script>
    // Footer opt-in without leaving the page (falls back to a normal post).
    (function () {
        var f = document.querySelector('form[data-subscribe]');
        if (!f || !window.fetch) return;
        f.addEventListener('submit', function (e) {
            e.preventDefault();
            fetch(f.action, { method: 'POST', headers: { Accept: 'application/json' }, body: new FormData(f) })
                .then(function (r) { return r.json(); })
                .then(function (d) { if (window.shopToast) shopToast(d.message); if (d.ok) f.reset(); })
                .catch(function () { f.submit(); });
        });
    })();
</script>
