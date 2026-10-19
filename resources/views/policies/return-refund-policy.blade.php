@extends('layouts.guest')

@php
    \App\Services\SeoService::set(
        'Return, Refund & Exchange Policy | ' . config('app.name'),
        'Goods Essentials return policy: 14 days to report a return, refund or exchange, with details on eligibility, damaged items and Cash on Delivery refunds.',
    );
@endphp

@section('content')
<div class="container-shop mx-auto max-w-4xl px-4 py-10 sm:py-14">
    <header class="mb-8 rounded-3xl bg-pink-50 px-6 py-8 sm:px-10">
        <p class="text-sm font-bold uppercase tracking-wider text-pink-600">Goods Essentials</p>
        <h1 class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">Return, Refund & Exchange Policy</h1>
        <p class="mt-3 text-sm leading-6 text-gray-600">Please review the conditions below before requesting a return, refund, or exchange.</p>
    </header>

    <div class="space-y-7 text-sm leading-7 text-gray-700 sm:text-base">
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Request Period</h2>
            <p>Contact Goods Essentials within 14 days of delivery to report an eligible return or exchange request. Requests received after this period may not be accepted. Please contact us using the details listed on our Contact Us page and include your order number and a description of the issue.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Eligibility</h2>
            <p>To be considered for an exchange or return, the product should be unused, in its original condition and packaging, with all tags, accessories, and labels intact where applicable. The original order receipt or invoice is required. Items may be exchanged subject to stock availability and may be valued at the current selling price.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Damaged, Defective, Incorrect, or Missing Items</h2>
            <p>If an item arrives damaged, defective, or different from what you ordered, contact us as soon as possible and within 14 days of delivery. We may ask for clear photographs, packaging details, or an unboxing video to assess the claim. If an item is missing, please report it within 2 days of delivery and provide an unboxing video if available. Where a claim is verified, Goods Essentials will advise the appropriate resolution and cover return shipping where applicable.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Non-Returnable Items and Exclusions</h2>
            <p>Items sold under special promotions, Buy One Get One offers, or discounts of 50% or more may be excluded from returns or exchanges. Certain personal-care, hygiene, accessory, or other product categories may also be excluded where stated on the product page or at checkout. Change-of-mind returns are not accepted unless expressly stated otherwise.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Refunds</h2>
            <p>Refund requests are reviewed after the returned item or claim has been assessed. If a refund is approved, we will notify you of the amount and the method used to process it. The time for the funds to appear may depend on the payment provider or bank. If an order is cancelled by Goods Essentials after payment, the amount paid for that order will be refunded using an appropriate payment method.</p>
            <p class="mt-2">For Cash on Delivery orders, a refund method will be agreed with the customer if a refund is approved.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Return Shipping</h2>
            <p>If the return is due to a verified damaged, defective, or incorrect item, Goods Essentials will cover the return shipping cost. For other approved requests, return shipping costs may be the customer's responsibility. Please wait for instructions from customer support before sending an item back.</p>
        </section>
        <section>
            <h2 class="mb-2 text-xl font-bold text-gray-900">Contact Us</h2>
            <p>To start a request, contact Goods Essentials through the contact information published on our website. Include your order number, the item concerned, and relevant photographs or other supporting information.</p>
        </section>
        <x-shop.policy-contact />
    </div>
</div>
@endsection
