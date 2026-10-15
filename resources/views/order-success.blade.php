@extends('layouts.guest')

@php $money = fn ($v) => money($v); @endphp

@section('pageTitle', 'Order placed | ' . config('app.name'))

@section('content')

<section class="border-b border-line bg-white/70">
    <div class="container-shop flex flex-col items-center py-10 text-center">
        <span class="grid size-16 place-items-center rounded-full bg-mint-100 text-mint-600">
            <x-shop.icon name="check" class="size-8" />
        </span>
        <h1 class="mt-4 text-3xl font-bold">Thank you, {{ $order->first_name }}!</h1>
        <p class="mt-1 text-sm text-muted">We've received your order.</p>
        <p class="mt-4 rounded-full bg-lavender-100 px-5 py-2 text-sm font-extrabold text-brand-500">Order {{ $order->order_number }}</p>

        @if (session('account_created'))
            <p class="mt-4 max-w-md text-sm text-muted">Your account has been created and you're signed in, so you can follow your orders from your dashboard.</p>
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
                        <p class="mt-0.5 text-xs text-muted">Qty {{ $item->quantity }} × {{ $money($item->price) }}</p>
                    </div>
                    <span class="text-sm font-extrabold">{{ $money($item->total) }}</span>
                </li>
            @endforeach
        </ul>

        <dl class="mt-2 space-y-2 border-t border-line pt-4 text-sm">
            <div class="flex justify-between"><dt class="text-muted">Subtotal</dt><dd class="font-bold">{{ $money($order->subtotal) }}</dd></div>
            <div class="flex justify-between"><dt class="text-muted">Shipping</dt><dd class="font-bold">{{ $money($order->shipping) }}</dd></div>
            <div class="flex justify-between border-t border-line pt-3 text-base">
                <dt class="font-bold">Total</dt><dd class="font-extrabold text-brand-500">{{ $money($order->total) }}</dd>
            </div>
        </dl>
    </section>

    <aside class="space-y-4">
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
            <p class="flex justify-between"><span class="text-muted">Status</span><span class="font-bold capitalize">{{ $order->status }}</span></p>
            <p class="mt-2 flex justify-between"><span class="text-muted">Placed</span><span class="font-bold">{{ $order->created_at->format('M j, Y') }}</span></p>
        </div>

        <a href="{{ route('shop') }}"
           class="flex w-full items-center justify-center gap-2 rounded-full bg-brand-500 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600">
            Continue shopping <x-shop.icon name="arrow-right" class="size-4" />
        </a>
    </aside>
</div>
@endsection
