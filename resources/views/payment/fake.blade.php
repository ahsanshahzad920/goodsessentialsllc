@extends('layouts.guest')

@section('pageTitle', 'Test payment | ' . config('app.name'))

@section('content')
<div class="container-shop py-12">
    <div class="mx-auto max-w-md rounded-3xl border border-line bg-white p-8 text-center shadow-sm">
        <span class="inline-block rounded-full bg-sun-100 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-sun-600">
            Test gateway · local only
        </span>

        <h1 class="mt-4 text-2xl font-bold">Pay {{ money($order->total) }}</h1>
        <p class="mt-1 text-sm text-muted">Order {{ $order->order_number }}</p>

        <form method="POST" action="{{ route('payment.callback', $order->order_number) }}" class="mt-6 space-y-3">
            @csrf
            <button type="submit" name="outcome" value="success"
                    class="w-full rounded-full bg-brand-500 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-500/30 hover:bg-brand-600">
                Simulate successful payment
            </button>
            <button type="submit" name="outcome" value="failed"
                    class="w-full rounded-full border border-line bg-white py-3 text-sm font-extrabold hover:border-blush-600 hover:text-blush-600">
                Simulate failed payment
            </button>
        </form>

        <p class="mt-4 text-xs text-muted">This page only exists on your local machine. Real payments use your chosen gateway.</p>
    </div>
</div>
@endsection
