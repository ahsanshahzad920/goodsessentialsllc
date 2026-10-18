@extends('layouts.guest')

@php
    // Session cart: [ key => [product_id, product_title, product_price, product_image, quantity] ]
    $items    = collect(session('cart', []));
    $count    = $items->sum('quantity');
    $subtotal = $items->sum(fn ($i) => $i['quantity'] * $i['product_price']);
    $money    = fn ($v) => money($v);
    $canRemove = \Illuminate\Support\Facades\Route::has('cart.remove'); // change to your real remove route name
@endphp

@section('pageTitle', 'Your Cart | ' . config('app.name'))

@section('content')

{{-- Page header --}}
<section class="border-b border-line bg-white/70">
    <div class="container-shop py-6">
        <p class="text-[11px] font-extrabold uppercase tracking-wider text-brand-500">Cart</p>
        <h1 class="mt-1 text-3xl font-bold">Your cart</h1>
        <p class="mt-0.5 text-xs text-muted"><span class="js-item-count">{{ $count }}</span> {{ \Illuminate\Support\Str::plural('item', $count) }}</p>
    </div>
</section>

<div class="container-shop py-8">
    @if ($items->isEmpty())
        {{-- Empty state --}}
        <div class="mx-auto max-w-md rounded-3xl border border-line bg-white p-10 text-center">
            <span class="mx-auto grid size-16 place-items-center rounded-full bg-lavender-100 text-brand-500">
                <x-shop.icon name="cart" class="size-7" />
            </span>
            <h2 class="mt-5 text-xl font-semibold">Your cart is empty</h2>
            <p class="mt-1 text-sm text-muted">Looks like you haven't added anything yet.</p>
            <a href="{{ route('shop') }}"
               class="mt-6 inline-flex items-center gap-2 rounded-full bg-brand-500 px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600">
                Start shopping <x-shop.icon name="arrow-right" class="size-4" />
            </a>
        </div>
    @else
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem] lg:items-start">

            {{-- Items --}}
            <ul id="cart-list" class="space-y-3">
                @foreach ($items as $key => $details)
                    @php
                        $image = ! empty($details['product_image'])
                            ? asset('storage/' . $details['product_image'])
                            : asset('images/product-placeholder.svg');
                        $url = route('productDetails', $details['product_slug'] ?? $details['product_id']);
                    @endphp

                    <li class="js-row flex gap-4 rounded-2xl border border-line bg-white p-3 shadow-sm transition aria-busy:opacity-60 sm:p-4"
                        data-product-id="{{ $details['product_id'] }}"
                        data-price="{{ $details['product_price'] }}">

                        <a href="{{ $url }}" class="shrink-0">
                            <img src="{{ $image }}" alt="{{ $details['product_title'] }}" loading="lazy"
                                 class="size-20 rounded-xl bg-mist-100 object-cover sm:size-24">
                        </a>

                        <div class="flex min-w-0 flex-1 flex-col">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="line-clamp-2 font-sans text-sm font-bold leading-snug">
                                    <a href="{{ $url }}">{{ $details['product_title'] }}</a>
                                </h3>
                                <p class="js-line-total shrink-0 text-base font-extrabold">{{ $money($details['quantity'] * $details['product_price']) }}</p>
                            </div>
                            <p class="mt-0.5 text-xs text-muted">{{ $money($details['product_price']) }} each</p>

                            <div class="mt-auto flex items-center justify-between gap-3 pt-3">
                                {{-- Quantity stepper --}}
                                <div class="inline-flex items-center rounded-full border border-line bg-white">
                                    <button type="button" data-action="decrement" @disabled($details['quantity'] <= 1)
                                            class="js-qty grid size-8 place-items-center rounded-full text-ink hover:bg-brand-50 disabled:cursor-not-allowed disabled:opacity-40"
                                            aria-label="Decrease quantity of {{ $details['product_title'] }}">
                                        <x-shop.icon name="minus" class="size-3.5" />
                                    </button>
                                    <span class="js-qty-value w-8 text-center text-sm font-bold" aria-live="polite">{{ $details['quantity'] }}</span>
                                    <button type="button" data-action="increment"
                                            class="js-qty grid size-8 place-items-center rounded-full text-ink hover:bg-brand-50 disabled:opacity-40"
                                            aria-label="Increase quantity of {{ $details['product_title'] }}">
                                        <x-shop.icon name="plus" class="size-3.5" />
                                    </button>
                                </div>

                                {{-- Remove (only rendered if a remove route exists) --}}
                                @if ($canRemove)
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $details['product_id'] }}">
                                        <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-muted hover:text-blush-600">
                                            <x-shop.icon name="trash" class="size-3.5" /> Remove
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- Order summary --}}
            <aside class="rounded-2xl border border-line bg-white p-5 shadow-sm lg:sticky lg:top-28" aria-label="Order summary">
                <h2 class="text-lg font-semibold">Order summary</h2>

                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Subtotal (<span class="js-item-count">{{ $count }}</span> items)</dt>
                        <dd class="js-grand-total font-bold">{{ $money($subtotal) }}</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-line pt-3 text-base">
                        <dt class="font-bold">Total</dt>
                        <dd class="js-grand-total font-extrabold">{{ $money($subtotal) }}</dd>
                    </div>
                </dl>

                <a href="{{ route('checkout') }}"
                   class="mt-5 flex w-full items-center justify-center gap-2 rounded-full bg-brand-500 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600">
                    Proceed to checkout <x-shop.icon name="arrow-right" class="size-4" />
                </a>
                <a href="{{ route('shop') }}" class="mt-3 block text-center text-xs font-bold text-brand-500 hover:text-brand-600">Continue shopping</a>

                <p class="mt-4 flex items-center justify-center gap-1.5 text-[11px] text-muted">
                    <x-shop.icon name="lock" class="size-3.5" /> Secure checkout. Taxes and shipping are finalised at checkout.
                </p>
            </aside>
        </div>
    @endif
</div>
@endsection

@push('script')
<script>
    (function () {
        var list = document.getElementById('cart-list');
        if (!list) return;

        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        var endpoints = {
            increment: "{{ route('increment.session') }}",
            decrement: "{{ route('decrement.session') }}"
        };

        var currency = @json(config('shop.currency'));

        function money(n) {
            var num = Number(n).toLocaleString('en-US', {
                minimumFractionDigits: currency.decimals,
                maximumFractionDigits: currency.decimals
            });
            var gap = currency.space ? ' ' : '';
            return currency.position === 'after' ? num + gap + currency.symbol : currency.symbol + gap + num;
        }

        // Sum quantities / totals from the DOM (used as fallback and for the item counters)
        function summarise() {
            var qty = 0, total = 0;
            list.querySelectorAll('.js-row').forEach(function (row) {
                var q = parseInt(row.querySelector('.js-qty-value').textContent, 10) || 0;
                qty += q;
                total += q * parseFloat(row.dataset.price);
            });
            return { qty: qty, total: total };
        }

        function renderCounts(sum) {
            document.querySelectorAll('.js-item-count, [data-cart-count]').forEach(function (el) { el.textContent = sum.qty; });
        }

        list.addEventListener('click', async function (e) {
            var btn = e.target.closest('.js-qty');
            if (!btn || btn.disabled) return;

            var row = btn.closest('.js-row');
            var action = btn.dataset.action;
            var valueEl = row.querySelector('.js-qty-value');
            var buttons = row.querySelectorAll('.js-qty');

            buttons.forEach(function (b) { b.disabled = true; });
            row.setAttribute('aria-busy', 'true');

            try {
                var res = await fetch(endpoints[action], {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ product_id: row.dataset.productId })
                });
                if (!res.ok) throw new Error('Request failed: ' + res.status);
                var data = await res.json();

                var current = parseInt(valueEl.textContent, 10) || 1;
                var next = (data.quantity !== undefined)
                    ? parseInt(data.quantity, 10)
                    : current + (action === 'increment' ? 1 : -1);
                valueEl.textContent = next;

                var line = (data.total !== undefined) ? data.total : next * parseFloat(row.dataset.price);
                row.querySelector('.js-line-total').textContent = money(line);

                var sum = summarise();
                var grand = (data.grandTotal !== undefined) ? data.grandTotal : sum.total;
                document.querySelectorAll('.js-grand-total').forEach(function (el) { el.textContent = money(grand); });
                renderCounts(sum);
            } catch (err) {
                console.error('Cart update error:', err);
            } finally {
                row.removeAttribute('aria-busy');
                buttons.forEach(function (b) { b.disabled = false; });
                // never let quantity go below 1 from the stepper
                row.querySelector('[data-action="decrement"]').disabled = (parseInt(valueEl.textContent, 10) <= 1);
            }
        });
    })();
</script>
@endpush
