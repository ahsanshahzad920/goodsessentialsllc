@extends('layouts.guest')

@php
    $user = Auth::user();
    $money = fn ($v) => money($v);
    $wantsAccount = (bool) old('create_account');

    // users table stores one `name`; split it to pre-fill the two fields
    $nameParts = explode(' ', trim($user->name ?? ''), 2);
    $firstName = $user->first_name ?? ($nameParts[0] ?? '');
    $lastName  = $user->last_name  ?? ($nameParts[1] ?? '');
@endphp

@section('pageTitle', 'Checkout | ' . config('app.name'))

@section('content')

{{-- Page header --}}
<section class="border-b border-line bg-white/70">
    <div class="container-shop py-6">
        <nav aria-label="Breadcrumb" class="mb-2 text-xs text-muted">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('shop') }}" class="hover:text-brand-500">Shop</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('cart') }}" class="hover:text-brand-500">Cart</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-bold text-ink">Checkout</li>
            </ol>
        </nav>
        <p class="text-[11px] font-extrabold uppercase tracking-wider text-brand-500">Almost yours</p>
        <h1 class="mt-1 text-3xl font-bold">Checkout</h1>
        <p class="mt-0.5 text-xs text-muted">Review your order and enter your delivery details.</p>
    </div>
</section>

<div class="container-shop py-8">
    @if (empty($lines))
        <div class="mx-auto max-w-md rounded-3xl border border-line bg-white p-10 text-center">
            <span class="mx-auto grid size-16 place-items-center rounded-full bg-lavender-100 text-brand-500">
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

        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST"
              class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_24rem] lg:items-start">
            @csrf

            <div class="space-y-6">

                {{-- Delivery details --}}
                <section class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-7">
                    <h2 class="text-lg font-semibold">Delivery details</h2>
                    <p class="mt-0.5 text-xs text-muted">
                        @auth Ordering as {{ $user->email }}. @else No account needed. Just fill in your details below. @endauth
                    </p>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <x-shop.input name="first_name" label="First name" :value="$firstName" :required="true" autocomplete="given-name" placeholder="Enter first name" />
                        <x-shop.input name="last_name"  label="Last name"  :value="$lastName"  :required="true" autocomplete="family-name" placeholder="Enter last name" />
                        <x-shop.input name="phone" type="tel"   label="Phone number"  :value="$user->phone ?? ''" :required="true" autocomplete="tel" placeholder="Enter phone number" />
                        <x-shop.input name="email" type="email" label="Email address" :value="$user->email ?? ''" :required="true" autocomplete="email" placeholder="example@email.com" />
                        <x-shop.input name="address" label="Street address" :required="true" autocomplete="street-address" placeholder="House number and street name" class="sm:col-span-2" />
                        <x-shop.input name="city" label="City" :required="true" autocomplete="address-level2" placeholder="Enter city" />
                        <x-shop.input name="zip_code" label="ZIP / Postal code" :required="true" autocomplete="postal-code" placeholder="Enter postal code" />

                        <div class="sm:col-span-2">
                            <label for="f-notes" class="mb-1.5 block text-sm font-semibold">
                                Additional notes <span class="font-normal text-muted">(optional)</span>
                            </label>
                            <textarea id="f-notes" name="notes" rows="3" placeholder="Delivery instructions or special requests..."
                                      class="block w-full resize-y rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/15">{{ old('notes') }}</textarea>
                            @error('notes') <p class="mt-1 text-xs font-semibold text-blush-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
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
                            Already have an account? <a href="{{ route('login') }}" class="font-bold text-brand-500 hover:text-brand-600">Log in</a>
                            (your cart will be kept).
                        </p>
                    </section>
                @endguest

                {{-- Reassurance --}}
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach ([['lock', 'Secure checkout'], ['truck', 'Careful packaging'], ['heart', 'Made with love']] as [$icon, $text])
                        <div class="flex items-center gap-3 rounded-2xl border border-line bg-white p-4">
                            <x-shop.icon :name="$icon" class="size-5 text-brand-500" />
                            <span class="text-xs font-bold">{{ $text }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order summary --}}
            <aside class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm lg:sticky lg:top-28" aria-label="Order summary">
                <div class="bg-lavender-100 px-5 py-4">
                    <h2 class="text-lg font-semibold">Your order</h2>
                    <p class="text-xs text-muted">{{ count($lines) }} {{ \Illuminate\Support\Str::plural('product', count($lines)) }}</p>
                </div>

                <div class="space-y-4 p-5">
                    <ul class="max-h-72 space-y-3 overflow-y-auto pr-1">
                        @foreach ($lines as $line)
                            @php
                                $p = $line['product'];
                                $img = $p->product_image ? asset('storage/' . $p->product_image) : asset('images/product-placeholder.svg');
                            @endphp
                            <li class="flex items-start gap-3">
                                <img src="{{ $img }}" alt="" class="size-12 shrink-0 rounded-xl bg-mist-100 object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-2 text-sm font-bold leading-snug">{{ $p->product_title }}</p>
                                    <p class="mt-0.5 text-xs text-muted">Qty {{ $line['quantity'] }} × {{ $money($line['price']) }}</p>
                                </div>
                                <span class="shrink-0 text-sm font-extrabold">{{ $money($line['total']) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <dl class="space-y-3 border-t border-dashed border-line pt-4 text-sm">
                        <div class="flex justify-between"><dt class="text-muted">Subtotal</dt><dd class="font-bold">{{ $money($subtotal) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-muted">Shipping</dt><dd class="font-bold">{{ $money($shipping) }}</dd></div>
                        <div class="flex items-center justify-between border-t border-line pt-3">
                            <dt class="text-base font-bold">Total</dt>
                            <dd class="text-2xl font-extrabold text-brand-500">{{ $money($total) }}</dd>
                        </div>
                    </dl>

                    <div class="rounded-2xl bg-mist-100 p-4">
                        <label for="terms" class="flex cursor-pointer items-start gap-3">
                            <input type="checkbox" id="terms" name="terms" value="1" required @checked(old('terms'))
                                   class="mt-0.5 size-4 rounded border-line accent-brand-500">
                            <span class="text-xs leading-5 text-muted">
                                I have read and accept the <a href="#" class="font-bold text-brand-500 underline">Terms &amp; Conditions</a>.
                            </span>
                        </label>
                        @error('terms') <p class="mt-2 text-xs font-semibold text-blush-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" id="place-order"
                            class="flex w-full items-center justify-center gap-2 rounded-full bg-brand-500 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600 disabled:cursor-wait disabled:opacity-70">
                        Place Order <x-shop.icon name="arrow-right" class="size-4" />
                    </button>

                    <a href="{{ route('cart') }}" class="block text-center text-xs font-bold text-muted hover:text-brand-500">Back to cart</a>
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
        var cb  = document.getElementById('create_account');
        var box = document.getElementById('account-fields');
        if (cb && box) {
            var sync = function () {
                box.hidden = !cb.checked;
                box.querySelectorAll('input').forEach(function (i) { i.disabled = !cb.checked; });
            };
            cb.addEventListener('change', sync);
            sync();
        }

        // Prevent double submit
        var form = document.getElementById('checkout-form');
        var btn  = document.getElementById('place-order');
        if (form && btn) {
            form.addEventListener('submit', function () {
                if (form.checkValidity()) { btn.disabled = true; btn.firstChild.textContent = 'Placing order... '; }
            });
        }
    })();
</script>
@endpush
