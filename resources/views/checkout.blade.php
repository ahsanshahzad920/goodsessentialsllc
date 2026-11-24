@extends('layouts.guest')

@php
    $user = Auth::user();
    $money = fn ($v) => money($v);
    $wantsAccount = (bool) old('create_account');

    // users table stores one `name`; split it to pre-fill the two fields
    $nameParts = explode(' ', trim($user->name ?? ''), 2);
    $firstName = $user->first_name ?? ($nameParts[0] ?? '');
    $lastName  = $user->last_name  ?? ($nameParts[1] ?? '');

    $selectedMethod = old('payment_method', $defaultMethod ?? 'cod');

    $ttqContents = collect($lines ?? [])->map(fn ($line) => [
        'content_id'   => (string) $line['product']->id,
        'content_type' => 'product',
        'content_name' => $line['product']->product_title,
        'quantity'     => $line['quantity'],
        'price'        => (float) $line['price'],
    ])->values();
@endphp

@section('pageTitle', 'Checkout | ' . config('app.name'))

@section('content')

{{-- Page header --}}
<section class="border-b border-line bg-white/70">
    <div class="container-shop py-6">
        <nav aria-label="Breadcrumb" class="mb-2 text-xs text-muted">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('shop') }}" class="hover:text-brand-600">Shop</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('cart') }}" class="hover:text-brand-600">Cart</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-bold text-ink">Checkout</li>
            </ol>
        </nav>
        <p class="text-[11px] font-extrabold uppercase tracking-wider text-brand-600">Almost yours</p>
        <h1 class="mt-1 text-3xl font-bold">Checkout</h1>
        <p class="mt-0.5 text-xs text-muted">Review your order and enter your delivery details.</p>
    </div>
</section>

<div class="container-shop py-8">
    @if (empty($lines))
        <div class="mx-auto max-w-md rounded-3xl border border-line bg-white p-10 text-center">
            <span class="mx-auto grid size-16 place-items-center rounded-full bg-lavender-100 text-brand-600">
                <x-shop.icon name="cart" class="size-7" />
            </span>
            <h2 class="mt-5 text-xl font-semibold">Your cart is empty</h2>
            <p class="mt-1 text-sm text-muted">Add something lovely before checking out.</p>
            <a href="{{ route('shop') }}"
               class="mt-6 inline-flex items-center gap-2 rounded-full bg-brand-500 px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600">
                Explore the shop <x-shop.icon name="arrow-right" class="size-4" />
            </a>
        </div>
    @else
        @if ($errors->any())
            <div class="mb-6 rounded-2xl bg-blush-100 px-4 py-3 text-sm font-semibold text-blush-600" role="alert">
                Please check the highlighted fields and try again.
            </div>
        @endif

        <form id="checkout-form"
            action="{{ route('checkout.store') }}"
            method="POST"
            data-shipping-cod="{{ \App\Support\Shipping::for((float) $subtotal, 'cod') }}"
            data-shipping-online="{{ \App\Support\Shipping::for((float) $subtotal, 'card') }}"
            class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_24rem] lg:items-start">
            @csrf

            <div class="space-y-6">

                {{-- Delivery details --}}
                <section class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-7">
                    <h2 class="text-lg font-semibold">Delivery details</h2>
                    <p class="mt-0.5 text-xs text-muted">
                        @auth Ordering as {{ $user->email }}. @else No account needed. Just fill in your details below. @endauth
                    </p>

                    @php
                        $fieldCls = 'mt-1.5 block min-h-12 w-full rounded-xl border border-line bg-white px-4 text-base text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-100 aria-[invalid=true]:border-coral-600';
                        $pkCities = ['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta', 'Hyderabad', 'Gujranwala', 'Sialkot', 'Bahawalpur', 'Sargodha', 'Sukkur', 'Larkana', 'Sheikhupura', 'Rahim Yar Khan', 'Jhang', 'Dera Ghazi Khan', 'Gujrat', 'Sahiwal', 'Wah Cantt', 'Mardan', 'Kasur', 'Okara', 'Mingora', 'Nawabshah', 'Chiniot', 'Kamoke', 'Sadiqabad', 'Burewala', 'Jacobabad', 'Muzaffargarh', 'Abbottabad', 'Mirpur Khas', 'Khanewal', 'Hafizabad', 'Kohat', 'Dera Ismail Khan', 'Khanpur', 'Mandi Bahauddin', 'Pakpattan', 'Jhelum', 'Vehari', 'Attock', 'Bahawalnagar', 'Pattoki', 'Chishtian', 'Muzaffarabad', 'Mirpur (AJK)', 'Gilgit', 'Skardu', 'Lodhran', 'Toba Tek Singh', 'Narowal', 'Mianwali', 'Bhakkar', 'Layyah', 'Khushab', 'Chakwal'];
                        sort($pkCities);
                        $fullName = old('full_name', trim($firstName . ' ' . $lastName));
                    @endphp
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm font-bold text-ink sm:col-span-2">Full name
                            <input name="full_name" required autocomplete="name" value="{{ $fullName }}" placeholder="e.g. Ayesha Khan" class="{{ $fieldCls }}" @error('first_name') aria-invalid="true" @enderror>
                            @error('first_name') <span class="mt-1 block text-xs font-bold text-coral-700">{{ $message }}</span> @enderror
                        </label>
                        <label class="block text-sm font-bold text-ink">Mobile number
                            <input name="phone" type="tel" inputmode="tel" required autocomplete="tel" data-pk-phone
                                   value="{{ old('phone', $user->phone ?? '') }}" placeholder="03XX-XXXXXXX" class="{{ $fieldCls }}" @error('phone') aria-invalid="true" @enderror>
                            <span class="mt-1 block text-xs font-normal text-muted" data-phone-hint>We'll confirm your order on this number (WhatsApp/call).</span>
                            @error('phone') <span class="mt-1 block text-xs font-bold text-coral-700">{{ $message }}</span> @enderror
                        </label>
                        <label class="block text-sm font-bold text-ink">City
                            <input name="city" required autocomplete="address-level2" list="pk-cities" value="{{ old('city') }}" placeholder="Start typing your city" class="{{ $fieldCls }}" @error('city') aria-invalid="true" @enderror>
                            <datalist id="pk-cities">@foreach ($pkCities as $c)<option value="{{ $c }}">@endforeach</datalist>
                            @error('city') <span class="mt-1 block text-xs font-bold text-coral-700">{{ $message }}</span> @enderror
                        </label>
                        <label class="block text-sm font-bold text-ink sm:col-span-2">Full address
                            <input name="address" required autocomplete="street-address" value="{{ old('address') }}" placeholder="House no., street, area / mohalla, nearby landmark" class="{{ $fieldCls }}" @error('address') aria-invalid="true" @enderror>
                            @error('address') <span class="mt-1 block text-xs font-bold text-coral-700">{{ $message }}</span> @enderror
                        </label>
                        <label class="block text-sm font-bold text-ink sm:col-span-2">Email <span class="font-normal text-muted">(optional — for an order confirmation email)</span>
                            <input name="email" type="email" autocomplete="email" value="{{ old('email', $user->email ?? '') }}" placeholder="example@email.com" class="{{ $fieldCls }}" @error('email') aria-invalid="true" @enderror>
                            @error('email') <span class="mt-1 block text-xs font-bold text-coral-700">{{ $message }}</span> @enderror
                        </label>

                        <div class="sm:col-span-2">
                            <label for="f-notes" class="mb-1.5 block text-sm font-semibold">
                                Additional notes <span class="font-normal text-muted">(optional)</span>
                            </label>
                            <textarea id="f-notes" name="notes" rows="3" placeholder="Delivery instructions or special requests..."
                                      class="block w-full resize-y rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/15">{{ old('notes', session('checkout_note')) }}</textarea>
                            @error('notes') <p class="mt-1 text-xs font-semibold text-blush-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                {{-- Payment method --}}
                <section class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-7">
                    <h2 class="text-lg font-semibold">Payment method</h2>
                    <p class="mt-0.5 text-xs text-muted">Choose how you'd like to pay.</p>

                    @php
                        // Look of each method: idle icon colours + what changes when it is selected
                        $paymentStyles = [
                            'cod' => [
                                'card'  => 'hover:border-mint-600/40 peer-checked:border-mint-600 peer-checked:bg-mint-100 peer-checked:shadow-mint-600/20',
                                'idle'  => 'bg-mint-100 text-mint-600',
                                'icon'  => 'group-has-[:checked]/pay:bg-mint-600 group-has-[:checked]/pay:text-white',
                                'title' => 'group-has-[:checked]/pay:text-mint-600',
                                'dot'   => 'group-has-[:checked]/pay:border-mint-600 group-has-[:checked]/pay:bg-mint-600',
                                'hint'  => "You'll pay " . $money($total) . " in cash when your order arrives.",
                            ],
                            'card' => [
                                'card'  => 'hover:border-brand-500/40 peer-checked:border-brand-500 peer-checked:bg-lavender-100 peer-checked:shadow-brand-500/20',
                                'idle'  => 'bg-lavender-100 text-brand-600',
                                'icon'  => 'group-has-[:checked]/pay:bg-brand-500 group-has-[:checked]/pay:text-white',
                                'title' => 'group-has-[:checked]/pay:text-brand-600',
                                'dot'   => 'group-has-[:checked]/pay:border-brand-500 group-has-[:checked]/pay:bg-brand-500',
                                'hint'  => "You'll be taken to our secure payment page next.",
                            ],
                        ];
                    @endphp

                    <div class="mt-5 grid gap-3 sm:grid-cols-2" role="radiogroup" aria-label="Payment method">
                        @foreach ($paymentMethods as $key => $method)
                            @php $s = $paymentStyles[$key] ?? $paymentStyles['card']; @endphp

                            <label class="group/pay relative block cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $key }}" required
                                    data-hint="{{ $s['hint'] ?? ($method['description'] ?? '') }}"
                                    class="peer sr-only" @checked($selectedMethod === $key)>

                                <span class="flex h-full items-start gap-3 rounded-2xl border-2 border-line bg-white p-4 transition duration-200 peer-checked:shadow-lg peer-focus-visible:ring-2 peer-focus-visible:ring-brand-500/40 {{ $s['card'] }}">

                                    <span class="grid size-11 shrink-0 place-items-center rounded-xl transition duration-200 {{ $s['idle'] }} {{ $s['icon'] }}">
                                        <x-shop.icon :name="$method['icon'] ?? 'cart'" class="size-5" />
                                    </span>

                                    <span class="min-w-0 flex-1">
                                        <span class="block text-sm font-extrabold transition {{ $s['title'] }}">{{ $method['label'] }}</span>
                                        <span class="mt-0.5 block text-xs text-muted">{{ $method['description'] ?? '' }}</span>
                                    </span>

                                    {{-- selected tick --}}
                                    <span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full border-2 border-line bg-white text-transparent transition duration-200 group-has-[:checked]/pay:text-white {{ $s['dot'] }}">
                                        <x-shop.icon name="check" class="size-3" />
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @error('payment_method')
                        <p class="mt-3 text-xs font-semibold text-blush-600">{{ $message }}</p>
                    @enderror
                </section>

                {{-- Optional account (guests only) --}}
                @guest
                    <section class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-7">
                        <label for="create_account" class="flex cursor-pointer items-start gap-3">
                            <input type="checkbox" id="create_account" name="create_account" value="1" @checked($wantsAccount)
                                   class="mt-1 size-4 rounded border-line accent-brand-500">
                            <span>
                                <span class="block text-sm font-bold">Create an account <span class="font-normal text-muted">(optional)</span></span>
                                <span class="block text-xs text-muted">Track your orders and check out faster next time. We'll use the details above.</span>
                            </span>
                        </label>

                        <div id="account-fields" class="mt-5 grid gap-4 sm:grid-cols-2" @unless($wantsAccount) hidden @endunless>
                            <x-shop.input name="password" type="password" label="Password" :required="true" autocomplete="new-password" placeholder="Create a password" />
                            <x-shop.input name="password_confirmation" type="password" label="Confirm password" :required="true" autocomplete="new-password" placeholder="Repeat your password" />
                        </div>

                        <p class="mt-4 text-xs text-muted">
                            Already have an account? <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-700">Log in</a>
                            (your cart will be kept).
                        </p>
                    </section>
                @endguest

                {{-- Reassurance --}}
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach ([['lock', 'Secure checkout'], ['truck', 'Careful packaging'], ['heart', 'Made with love']] as [$icon, $text])
                        <div class="flex items-center gap-3 rounded-2xl border border-line bg-white p-4">
                            <x-shop.icon :name="$icon" class="size-5 text-brand-600" />
                            <span class="text-xs font-bold">{{ $text }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order summary: collapsible on phones, sticky on desktop --}}
            <aside class="overflow-hidden rounded-card border border-line bg-white shadow-soft lg:sticky lg:top-24" aria-label="Order summary">
                <details class="group" data-summary>
                    <summary class="flex min-h-14 cursor-pointer list-none items-center justify-between gap-3 bg-lavender-100 px-5 py-3 lg:pointer-events-none [&::-webkit-details-marker]:hidden">
                        <span>
                            <span class="block font-display text-lg font-semibold">Your order</span>
                            <span class="block text-xs text-muted">{{ count($lines) }} {{ \Illuminate\Support\Str::plural('item', count($lines)) }} · <span class="lg:hidden group-open:hidden">tap to see</span></span>
                        </span>
                        <span class="flex items-center gap-2 font-extrabold text-coral-600">
                            <span data-summary-total>{{ $money($total) }}</span>
                            <x-shop.icon name="chevron-down" class="size-5 text-muted transition-transform group-open:rotate-180 lg:hidden" />
                        </span>
                    </summary>
                    <ul class="max-h-72 space-y-3 overflow-y-auto border-b border-line p-5">
                        @foreach ($lines as $line)
                            @php
                                $p = $line['product'];
                                $img = \App\Support\WebpImage::url($p->product_image) ?? ($p->product_image ? asset('storage/' . $p->product_image) : asset('images/product-placeholder.svg'));
                            @endphp
                            <li class="flex items-start gap-3">
                                <img src="{{ $img }}" alt="" width="48" height="48" class="size-12 shrink-0 rounded-xl bg-frame object-contain p-0.5">
                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-2 text-sm font-bold leading-snug">{{ $p->product_title }}</p>
                                    <p class="mt-0.5 text-xs text-muted">Qty {{ $line['quantity'] }} × {{ $money($line['price']) }}</p>
                                    @if (! empty($line['color_name']))
                                        <x-shop.color-dot :name="$line['color_name']" :hex="$line['color_hex']" />
                                    @endif
                                </div>
                                <span class="shrink-0 text-sm font-extrabold">{{ $money($line['total']) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </details>
                <script>
                    // Open by default on desktop (the summary is always useful there).
                    if (window.matchMedia('(min-width: 1024px)').matches) document.querySelector('[data-summary]').open = true;
                </script>

                <div class="space-y-4 p-5">
                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted">Subtotal</dt>
                            <dd class="font-bold">{{ $money($subtotal) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted">Delivery</dt>
                            <dd id="shipping-amount" class="font-bold">{{ $shipping > 0 ? $money($shipping) : 'FREE' }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-line pt-3">
                            <dt class="text-base font-bold">Total</dt>
                            <dd id="total-amount" class="text-2xl font-extrabold text-coral-600" data-subtotal="{{ $subtotal }}">{{ $money($total) }}</dd>
                        </div>
                    </dl>

                    <label for="terms" class="flex cursor-pointer items-start gap-3 rounded-2xl bg-mist-100 p-3">
                        <input type="checkbox" id="terms" name="terms" value="1" required @checked(old('terms'))
                               class="mt-0.5 size-5 rounded border-line accent-brand-600">
                        <span class="text-xs leading-5 text-muted">
                            I accept the <a href="{{ route('terms-and-conditions') }}" target="_blank" class="font-bold text-brand-600 underline">Terms &amp; Conditions</a>.
                        </span>
                    </label>
                    @error('terms') <p class="text-xs font-bold text-coral-700">{{ $message }}</p> @enderror
                    @error('stock') <p class="rounded-xl bg-coral-50 p-3 text-xs font-bold text-coral-700" role="alert">{{ $message }}</p> @enderror

                    <button type="submit" id="place-order" class="btn btn-primary btn-lg w-full disabled:cursor-wait">
                        <x-shop.icon name="lock" class="size-5" />
                        <span id="place-order-label">Confirm Order — Pay on Delivery</span>
                    </button>
                    <p id="payment-hint" class="text-center text-xs text-muted" aria-live="polite"></p>

                    <ul class="grid grid-cols-2 gap-2 text-[11px] font-bold text-ink">
                        <li class="flex items-center gap-1.5 rounded-xl bg-mint-100 px-2.5 py-2"><x-shop.icon name="banknote" class="size-4 text-mint-600" /> Pay cash on delivery</li>
                        <li class="flex items-center gap-1.5 rounded-xl bg-mint-100 px-2.5 py-2"><x-shop.icon name="package" class="size-4 text-mint-600" /> Open parcel first</li>
                        <li class="flex items-center gap-1.5 rounded-xl bg-mint-100 px-2.5 py-2"><x-shop.icon name="truck" class="size-4 text-mint-600" /> 1–3 day delivery</li>
                        <li class="flex items-center gap-1.5 rounded-xl bg-mint-100 px-2.5 py-2"><x-shop.icon name="rotate-ccw" class="size-4 text-mint-600" /> 14-day returns</li>
                    </ul>

                    <a href="{{ route('cart') }}" class="block text-center text-xs font-bold text-muted hover:text-brand-600">Back to cart</a>
                </div>
            </aside>
        </form>
    @endif
</div>
@endsection

@push('script')
<script>
    (function () {
        // Show password fields only when "Create an account" is ticked
        var cb = document.getElementById('create_account');
        var box = document.getElementById('account-fields');

        if (cb && box) {
            var syncAccountFields = function () {
                box.hidden = !cb.checked;

                box.querySelectorAll('input').forEach(function (input) {
                    input.disabled = !cb.checked;
                });
            };

            cb.addEventListener('change', syncAccountFields);
            syncAccountFields();
        }

        // Payment method elements
        var label = document.getElementById('place-order-label');
        var radios = document.querySelectorAll(
            'input[name="payment_method"]'
        );
        var hint = document.getElementById('payment-hint');

        // Shipping and total elements
        var form = document.getElementById('checkout-form');
        var shippingAmount = document.getElementById('shipping-amount');
        var totalAmount = document.getElementById('total-amount');

        // Format amounts in Pakistani Rupees
        function formatMoney(amount) {
            return new Intl.NumberFormat('en-PK', {
                style: 'currency',
                currency: 'PKR',
                maximumFractionDigits: 2
            }).format(amount);
        }

        // Update button text, payment hint, shipping and total
        function syncPaymentDetails() {
            var checked = document.querySelector(
                'input[name="payment_method"]:checked'
            );

            if (!checked) return;

            // Update button text
            if (label) {
                label.textContent = checked.value === 'card'
                    ? 'Continue to Payment'
                    : 'Confirm Order — Pay on Delivery';
            }

            // Update payment hint
            if (hint) {
                hint.textContent = checked.dataset.hint || '';
            }

            // Update shipping and total
            if (form && shippingAmount && totalAmount) {
                var shipping = checked.value === 'card'
                    ? Number(form.dataset.shippingOnline)
                    : Number(form.dataset.shippingCod);

                var subtotal = Number(totalAmount.dataset.subtotal);

                if (!Number.isFinite(shipping)) shipping = 0;
                if (!Number.isFinite(subtotal)) subtotal = 0;

                shippingAmount.textContent = shipping === 0 ? 'FREE' : formatMoney(shipping);
                totalAmount.textContent = formatMoney(subtotal + shipping);
                var st = document.querySelector('[data-summary-total]');
                if (st) st.textContent = formatMoney(subtotal + shipping);
            }
        }

        // Listen for payment method changes
        radios.forEach(function (radio) {
            radio.addEventListener('change', syncPaymentDetails);
        });

        // Set correct values on initial page load
        syncPaymentDetails();

        // Prevent double submission
        var btn = document.getElementById('place-order');

        if (form && btn) {
            form.addEventListener('submit', function () {
                if (form.checkValidity()) {
                    btn.disabled = true;

                    if (label) {
                        label.textContent = 'Processing...';
                    }
                }
            });
        }
    })();
</script>

@endpush

@if (! empty($lines))
    @push('script')
    <script>
        // TikTok InitiateCheckout: one real event per checkout page load.
        var checkoutContents = @js($ttqContents);
        ttqTrack('InitiateCheckout', {
            contents: checkoutContents,
            value: {{ (float) $subtotal }},
            currency: 'PKR',
        });

        // Meta Pixel InitiateCheckout: same cart contents, Meta's parameter shape.
        fbqTrack('InitiateCheckout', {
            content_ids: checkoutContents.map(function (c) { return c.content_id; }),
            contents: checkoutContents.map(function (c) { return { id: c.content_id, quantity: c.quantity }; }),
            num_items: checkoutContents.reduce(function (n, c) { return n + c.quantity; }, 0),
            value: {{ (float) $subtotal }},
            currency: 'PKR',
        }, @js($checkoutEventId ?? null));

        // GA4 begin_checkout
        gaEvent('begin_checkout', {
            value: {{ (float) $subtotal }},
            items: checkoutContents.map(function (c) { return { item_id: c.content_id, item_name: c.content_name, price: c.price, quantity: c.quantity }; }),
        });

        @if ($user && $user->email)
            {{-- Best-effort match data when available (logged-in customer).
                 Hashed server-side with PHP's hash() (plain SHA-256 hex digest,
                 the format TikTok's identify expects) — the raw email/phone
                 never reaches the page source. --}}
            ttqIdentify({
                email: @js(hash('sha256', strtolower(trim($user->email)))),
                @if ($user->phone)
                    phone_number: @js(hash('sha256', preg_replace('/[^0-9]/', '', $user->phone))),
                @endif
            });
        @endif
    </script>
    @endpush
@endif

@push('script')
<script>
    // Pakistani mobile number: live hint + capture for follow-up once valid.
    (function () {
        var input = document.querySelector('[data-pk-phone]');
        if (!input) return;
        var hint = document.querySelector('[data-phone-hint]');
        var form = document.getElementById('checkout-form');
        var sent = '';

        function normalize(v) {
            var d = (v || '').replace(/\D/g, '');
            if (d.indexOf('0092') === 0) d = d.slice(4);
            else if (d.indexOf('92') === 0 && d.length === 12) d = d.slice(2);
            if (d.length === 10 && d[0] === '3') d = '0' + d;
            return d;
        }
        function check() {
            var d = normalize(input.value), ok = /^03\d{9}$/.test(d);
            input.setAttribute('aria-invalid', input.value && !ok && d.length >= 11 ? 'true' : 'false');
            if (hint) hint.textContent = ok ? '✓ ' + d.slice(0, 4) + '-' + d.slice(4) + ' — we' + "'" + 'll confirm your order on this number.'
                                            : 'Format: 03XX-XXXXXXX';
            return ok ? d : null;
        }
        function capture() {
            var d = check();
            if (!d || d === sent) return;
            sent = d;
            var body = new FormData();
            body.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            body.append('phone', d);
            body.append('name', (form.querySelector('[name="full_name"]') || {}).value || '');
            body.append('city', (form.querySelector('[name="city"]') || {}).value || '');
            fetch(@json(route('checkout.capture')), { method: 'POST', body: body, keepalive: true }).catch(function () {});
        }
        input.addEventListener('input', check);
        input.addEventListener('blur', capture);
        // Refresh name/city on the captured row if they are filled in after the phone.
        ['full_name', 'city'].forEach(function (n) {
            var el = form.querySelector('[name="' + n + '"]');
            if (el) el.addEventListener('change', function () { sent = ''; capture(); });
        });
        if (input.value) check();
    })();
</script>
@endpush
