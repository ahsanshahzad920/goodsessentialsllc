@extends('layouts.guest')

@php
    $money = fn ($v) => money($v);
    $t = match ($order->status) { 'processing' => 'sky', 'shipped' => 'lavender', 'delivered' => 'mint', 'cancelled' => 'blush', default => 'sun' };
@endphp

@section('pageTitle', 'Order ' . $order->order_number . ' | ' . config('app.name'))

@section('content')

<section class="border-b border-line bg-white/70">
    <div class="container-shop py-6">
        <nav aria-label="Breadcrumb" class="mb-2 text-xs text-muted">
            <a href="{{ route('account.orders.index') }}" class="hover:text-brand-500">My orders</a> <span aria-hidden="true">/</span>
            <span class="font-bold text-ink">{{ $order->order_number }}</span>
        </nav>
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="text-3xl font-bold">Order {{ $order->order_number }}</h1>
            <span class="rounded-full bg-{{ $t }}-100 px-3 py-1 text-[11px] font-extrabold capitalize text-{{ $t }}-600">{{ $order->status }}</span>
        </div>
        <p class="mt-0.5 text-xs text-muted">Placed {{ $order->created_at->format('M j, Y') }} · Payment: {{ ucfirst($order->payment_status) }}</p>
    </div>
</section>

<div class="container-shop grid gap-6 py-8 lg:grid-cols-[minmax(0,1fr)_22rem] lg:items-start">

    <section class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
        <h2 class="text-lg font-semibold">Items</h2>
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

        <a href="{{ route('account.orders.index') }}"
           class="flex w-full items-center justify-center rounded-full border border-line bg-white py-3 text-sm font-extrabold hover:border-brand-500 hover:text-brand-500">
            Back to my orders
        </a>
    </aside>
</div>
@endsection
