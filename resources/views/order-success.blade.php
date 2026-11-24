@extends('layouts.guest')

@section('pageTitle', 'Order placed | ' . config('app.name'))

@section('content')

<section class="border-b border-line bg-white/70">
    <div class="container-shop flex flex-col items-center py-10 text-center">
        <span class="grid size-16 place-items-center rounded-full bg-mint-100 text-mint-600">
            <x-shop.icon name="check" class="size-8" />
        </span>
        <h1 class="mt-4 text-3xl font-bold">Thank you, {{ $order->first_name }}!</h1>
        <p class="mt-1 text-sm text-muted">
            @if ($order->needsOnlinePayment())
                Your order is saved. Complete the payment below to confirm it.
            @else
                We've received your order.
            @endif
        </p>
        <p class="mt-4 rounded-full bg-lavender-100 px-5 py-2 text-sm font-extrabold text-brand-600">Order {{ $order->order_number }}</p>

        @unless ($order->needsOnlinePayment())
            @php [$from, $to] = \App\Support\ProductPage::deliveryWindow($order->created_at); @endphp
            <div class="mt-5 grid w-full max-w-xl gap-3 text-left sm:grid-cols-2">
                <p class="flex items-start gap-3 rounded-card bg-mint-100 p-4 text-sm text-ink">
                    <x-shop.icon name="truck" class="mt-0.5 size-5 shrink-0 text-mint-600" />
                    <span>Expected delivery<br><strong>{{ $from->format('D, j M') }} – {{ $to->format('D, j M') }}</strong></span>
                </p>
                <p class="flex items-start gap-3 rounded-card bg-lavender-100 p-4 text-sm text-ink">
                    <x-shop.icon name="whatsapp" class="mt-0.5 size-5 shrink-0 text-[#0e7a3f]" />
                    <span>We'll confirm your order on WhatsApp / call at <strong>{{ \App\Support\PkPhone::display($order->phone) }}</strong></span>
                </p>
            </div>
        @endunless

        <a href="https://wa.me/?text={{ rawurlencode('I just ordered baby essentials from Goods Essentials — Cash on Delivery all over Pakistan: ' . route('index')) }}"
           target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm mt-5">
            <x-shop.icon name="whatsapp" class="size-4" /> Share with a friend
        </a>

        @if ($accountCreated ?? false)
            <p class="mt-4 max-w-md text-sm text-muted">Your account has been created and you're signed in, so you can follow your orders from "My Orders".</p>
        @endif
    </div>
</section>

<div class="container-shop grid gap-6 py-8 lg:grid-cols-[minmax(0,1fr)_22rem] lg:items-start">

    <section class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
        <h2 class="text-lg font-semibold">Order summary</h2>

        <ul class="mt-4 divide-y divide-line">
            @foreach ($order->items as $item)
                @php $img = $item->product_image ? asset('storage/' . $item->product_image) : asset('images/product-placeholder.svg'); @endphp
                <li class="flex items-center gap-4 py-3">
                    <img src="{{ $img }}" alt="" class="size-16 shrink-0 rounded-xl bg-mist-100 object-cover">
                    <div class="min-w-0 flex-1">
                        <p class="line-clamp-2 text-sm font-bold">{{ $item->product_title }}</p>
                        <p class="mt-0.5 text-xs text-muted">Qty {{ $item->quantity }} × {{ money($item->price) }}</p>
                        @if ($item->color_name)
                            <x-shop.color-dot :name="$item->color_name" :hex="$item->color_hex" />
                        @endif
                    </div>
                    <span class="text-sm font-extrabold">{{ money($item->total) }}</span>
                </li>
            @endforeach
        </ul>

        <dl class="mt-2 space-y-2 border-t border-line pt-4 text-sm">
            <div class="flex justify-between"><dt class="text-muted">Subtotal</dt><dd class="font-bold">{{ money($order->subtotal) }}</dd></div>
            <div class="flex justify-between"><dt class="text-muted">Shipping</dt><dd class="font-bold">{{ money($order->shipping) }}</dd></div>
            <div class="flex justify-between border-t border-line pt-3 text-base">
                <dt class="font-bold">Total</dt><dd class="font-extrabold text-brand-600">{{ money($order->total) }}</dd>
            </div>
        </dl>
    </section>

    <aside class="space-y-4">
        {{-- Payment --}}
        <div class="rounded-2xl border border-line bg-white p-5 shadow-sm text-sm">
            <h2 class="text-base font-semibold">Payment</h2>
            <p class="mt-3 flex justify-between"><span class="text-muted">Method</span><span class="font-bold">{{ $order->payment_method_label }}</span></p>
            <p class="mt-2 flex justify-between"><span class="text-muted">Status</span><span class="font-bold capitalize">{{ $order->payment_status }}</span></p>

            @if ($order->payment_method === 'cod')
                <p class="mt-3 border-t border-line pt-3 text-xs text-muted">Please keep {{ money($order->total) }} ready for when your order arrives.</p>
            @elseif ($order->needsOnlinePayment())
                <a href="{{ route('payment.start', $order->order_number) }}"
                   class="mt-4 flex w-full items-center justify-center gap-2 rounded-full bg-brand-600 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-700">
                    <x-shop.icon name="credit-card" class="size-4" /> Complete payment
                </a>
            @endif
        </div>

        <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
            <h2 class="text-base font-semibold">Delivery details</h2>
            <address class="mt-3 text-sm not-italic leading-6 text-muted">
                <span class="font-bold text-ink">{{ $order->full_name }}</span><br>
                {{ $order->address }}<br>
                {{ $order->city }} {{ $order->zip_code }}<br>
                {{ $order->phone }}<br>
                {{ $order->email }}
            </address>
            @if ($order->notes)
                <p class="mt-3 border-t border-line pt-3 text-xs text-muted"><span class="font-bold text-ink">Notes:</span> {{ $order->notes }}</p>
            @endif
        </div>

        <div class="rounded-2xl border border-line bg-white p-5 shadow-sm text-sm">
            <p class="flex justify-between"><span class="text-muted">Order status</span><span class="font-bold capitalize">{{ $order->status }}</span></p>
            <p class="mt-2 flex justify-between"><span class="text-muted">Placed</span><span class="font-bold">{{ $order->created_at->format('M j, Y') }}</span></p>
        </div>

        <a href="{{ route('shop') }}"
           class="flex w-full items-center justify-center gap-2 rounded-full border border-line bg-white py-3 text-sm font-extrabold hover:border-brand-500 hover:text-brand-600">
            Continue shopping <x-shop.icon name="arrow-right" class="size-4" />
        </a>
    </aside>
</div>

@if (($completeSet ?? collect())->isNotEmpty())
    <section aria-labelledby="set-title" class="container-shop space-y-4 pb-12">
        <x-section-header id="set-title" eyebrow="Complete the set" title="Moms also picked" />
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ($completeSet as $p)
                <x-product-card :product="$p" />
            @endforeach
        </div>
    </section>
@endif
@endsection

@push('script')
<script>
    // Hashed server-side (plain SHA-256 hex digest) so the raw email/phone
    // never reaches the page source — real customer data, known for certain
    // at this point regardless of guest vs. logged-in checkout.
    // Email is optional since Phase 6 — never send the hash of an empty string.
    ttqIdentify(@js(array_filter([
        'email'        => $order->email !== '' ? hash('sha256', strtolower(trim($order->email))) : null,
        'phone_number' => hash('sha256', preg_replace('/[^0-9]/', '', $order->phone)),
    ])));

    @if ($firePlaceAnOrder ?? false)
        // Confetti, once per order (same one-shot flag as the Purchase event). ~1KB, no library.
        (function () {
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            var c = document.createElement('canvas'), x = c.getContext('2d');
            c.setAttribute('aria-hidden', 'true');
            c.style.cssText = 'position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:80';
            document.body.appendChild(c);
            var W = c.width = innerWidth, H = c.height = innerHeight;
            var colors = ['#f26b5b', '#7c6ce0', '#bdebdd', '#ffe7a3', '#ffd9de'];
            var bits = Array.from({ length: 120 }, function () {
                return { x: W / 2, y: H / 3, vx: (Math.random() - .5) * 14, vy: Math.random() * -12 - 4, s: 4 + Math.random() * 6,
                         r: Math.random() * 6, vr: (Math.random() - .5) * .3, c: colors[(Math.random() * colors.length) | 0] };
            });
            var start = performance.now();
            (function tick(now) {
                var t = now - start;
                x.clearRect(0, 0, W, H);
                bits.forEach(function (b) {
                    b.vy += .35; b.vx *= .99; b.x += b.vx; b.y += b.vy; b.r += b.vr;
                    x.save(); x.translate(b.x, b.y); x.rotate(b.r); x.globalAlpha = Math.max(0, 1 - t / 2200);
                    x.fillStyle = b.c; x.fillRect(-b.s / 2, -b.s / 4, b.s, b.s / 2); x.restore();
                });
                t < 2200 ? requestAnimationFrame(tick) : c.remove();
            })(start);
        })();

        // Fires once (session flag consumed in CheckoutController::success()),
        // so refreshing this page never double-counts the same order.
        var orderContents = @js($order->items->map(fn ($item) => [
            'content_id'   => (string) $item->product_id,
            'content_type' => 'product',
            'content_name' => $item->product_title,
            'quantity'     => $item->quantity,
            'price'        => (float) $item->price,
        ]));

        ttqTrack('PlaceAnOrder', {
            contents: orderContents,
            value: {{ (float) $order->total }},
            currency: 'PKR',
        }, @js('place_order_' . $order->id));

        // Meta Pixel Purchase: fires under the same one-shot guard as
        // PlaceAnOrder above -- for COD this is the real conversion moment,
        // and for card orders this page load already coincides with payment
        // confirmation (see CheckoutController::success()).
        fbqTrack('Purchase', {
            content_ids: orderContents.map(function (c) { return c.content_id; }),
            content_type: 'product',
            contents: orderContents.map(function (c) { return { id: c.content_id, quantity: c.quantity }; }),
            value: {{ (float) $order->total }},
            currency: 'PKR',
        }, @js('purchase_' . $order->id)); // same id as the server-side Conversions API Purchase

        // GA4 purchase (same one-shot guard)
        gaEvent('purchase', {
            transaction_id: @js($order->order_number),
            value: {{ (float) $order->total }},
            shipping: {{ (float) $order->shipping }},
            items: orderContents.map(function (c) { return { item_id: c.content_id, item_name: c.content_name, price: c.price, quantity: c.quantity }; }),
        });
    @endif

    @if ($fireCompletePayment ?? false)
        // Prepaid (card) orders only — fires once, the same request the
        // gateway confirmed payment on (see PaymentController::applyResult()).
        ttqTrack('CompletePayment', {
            contents: @js($order->items->map(fn ($item) => [
                'content_id'   => (string) $item->product_id,
                'content_type' => 'product',
                'content_name' => $item->product_title,
                'quantity'     => $item->quantity,
                'price'        => (float) $item->price,
            ])),
            value: {{ (float) $order->total }},
            currency: 'PKR',
        }, @json('complete_payment_' . $order->id));
    @endif
</script>
@endpush
