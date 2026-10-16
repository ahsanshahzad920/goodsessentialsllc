@extends('layouts.guest')

@section('content')
<div class="container-shop mx-auto max-w-4xl px-4 py-10 sm:py-14">
    <header class="mb-8 rounded-3xl bg-pink-50 px-6 py-8 sm:px-10">
        <p class="text-sm font-bold uppercase tracking-wider text-pink-600">Goods Essentials</p>
        <h1 class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">Shipping & Service Policy</h1>
        <p class="mt-3 text-sm leading-6 text-gray-600">Information about delivery charges, estimated delivery times, and order handling.</p>
    </header>

    <div class="space-y-7 text-sm leading-7 text-gray-700 sm:text-base">
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Delivery Charges</h2>
            <ul class="list-disc space-y-1 pl-6">
                <li>Prepaid orders: PKR 200 delivery charge.</li>
                <li>Cash on Delivery (COD) orders: PKR 250 delivery charge.</li>
            </ul>
            <p class="mt-2">Any applicable delivery charges will be shown during checkout before you place your order.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Estimated Delivery Times</h2>
            <ul class="list-disc space-y-1 pl-6">
                <li>Within Karachi: approximately 1–2 working days.</li>
                <li>Outside Karachi: approximately 4–5 working days.</li>
                <li>During major sales or peak periods, delivery may take longer: approximately 2–3 working days within Karachi and 5–7 working days outside Karachi.</li>
            </ul>
            <p class="mt-2">These are estimates, not guaranteed delivery dates. Delays may occur due to courier operations, weather, public holidays, remote locations, or other circumstances outside our control.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Cash on Delivery and Advance Payment</h2>
            <p>Cash on Delivery is available only for orders below PKR 40,000. Orders above PKR 40,000 require a partial advance payment by bank transfer. The required advance amount and payment instructions should be confirmed with Goods Essentials before the order is processed.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Order Processing and Tracking</h2>
            <p>Orders are processed after the required order and payment details have been received and verified. If tracking information is available, it will be shared through the contact details provided with the order. Please ensure your phone number and delivery address are accurate when placing an order.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Lost, Delayed, or Damaged Packages</h2>
            <p>If your package is delayed, appears lost, arrives with a damaged seal, or contains damaged or missing products, contact us promptly. We may investigate with the courier and ask for photographs of the package, its seal, the received items, or an unboxing video. Investigations may take several working days.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Service and Support</h2>
            <p>For delivery questions, order updates, or help with a shipment, use the contact details listed on the Goods Essentials Contact Us page. For damaged, defective, incorrect, or missing products, please also review our Return, Refund & Exchange Policy.</p>
        </section>
    </div>
</div>
@endsection
