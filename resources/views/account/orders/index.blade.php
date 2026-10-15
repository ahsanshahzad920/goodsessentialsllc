@extends('layouts.guest')

@php
    $money = fn ($v) => money($v);
    $tone  = fn ($s) => match ($s) { 'processing' => 'sky', 'shipped' => 'lavender', 'delivered' => 'mint', 'cancelled' => 'blush', default => 'sun' };
@endphp

@section('pageTitle', 'My orders | ' . config('app.name'))

@section('content')

<section class="border-b border-line bg-white/70">
    <div class="container-shop flex flex-wrap items-end justify-between gap-3 py-6">
        <div>
            <p class="text-[11px] font-extrabold uppercase tracking-wider text-brand-500">My account</p>
            <h1 class="mt-1 text-3xl font-bold">My orders</h1>
            <p class="mt-0.5 text-xs text-muted">Hi {{ Auth::user()->name }}, here's everything you've ordered.</p>
        </div>
        <a href="{{ route('user.profile.show') }}"
           class="rounded-full border border-line bg-white px-5 py-2.5 text-xs font-bold hover:border-brand-500 hover:text-brand-500">Edit profile</a>
    </div>
</section>

<div class="container-shop py-8">
    @if ($orders->count())
        <ul class="space-y-3">
            @foreach ($orders as $order)
                @php $t = $tone($order->status); @endphp
                <li class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-line bg-white p-4 shadow-sm sm:p-5">
                    <div>
                        <p class="text-sm font-extrabold">{{ $order->order_number }}</p>
                        <p class="mt-0.5 text-xs text-muted">
                            {{ $order->created_at->format('M j, Y') }} · {{ $order->items_count }} {{ \Illuminate\Support\Str::plural('item', $order->items_count) }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="rounded-full bg-{{ $t }}-100 px-3 py-1 text-[11px] font-extrabold capitalize text-{{ $t }}-600">{{ $order->status }}</span>
                        <span class="text-base font-extrabold">{{ $money($order->total) }}</span>
                        <a href="{{ route('account.orders.show', $order) }}"
                           class="inline-flex items-center gap-1 rounded-full bg-lavender-100 px-4 py-2 text-xs font-extrabold text-brand-500 hover:bg-brand-500 hover:text-white">
                            View <x-shop.icon name="arrow-right" class="size-3.5" />
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-8">{{ $orders->links('vendor.pagination.shop') }}</div>
    @else
        <div class="mx-auto max-w-md rounded-3xl border border-line bg-white p-10 text-center">
            <span class="mx-auto grid size-16 place-items-center rounded-full bg-lavender-100 text-brand-500">
                <x-shop.icon name="cart" class="size-7" />
            </span>
            <h2 class="mt-5 text-xl font-semibold">No orders yet</h2>
            <p class="mt-1 text-sm text-muted">When you place an order it will show up here.</p>
            <a href="{{ route('shop') }}"
               class="mt-6 inline-flex items-center gap-2 rounded-full bg-brand-500 px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600">
                Start shopping <x-shop.icon name="arrow-right" class="size-4" />
            </a>
        </div>
    @endif
</div>
@endsection
