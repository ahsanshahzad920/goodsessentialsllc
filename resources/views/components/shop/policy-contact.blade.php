{{--
    Single rendered block for phone / email / address / hours, shared by the
    Contact page, footer, and all four policy pages so they can never drift
    out of sync — all four read from config/shop.php.
--}}
@php
    $contact = config('shop.contact', []);
@endphp
<section class="mt-2 rounded-2xl border border-gray-100 bg-gray-50 p-5 text-sm leading-6 text-gray-700">
    <h3 class="mb-2 text-base font-bold text-gray-900">Contact Us</h3>
    <ul class="space-y-1">
        @if (! empty($contact['phone']))
            <li>Phone: <a href="tel:{{ $contact['phone'] }}" class="font-semibold text-pink-600 hover:underline">{{ $contact['phone'] }}</a></li>
        @endif
        @if (! empty($contact['email']))
            <li>Email: <a href="mailto:{{ $contact['email'] }}" class="font-semibold text-pink-600 hover:underline">{{ $contact['email'] }}</a></li>
        @endif
        @if ($address = config('shop.address'))
            <li>Address: {{ $address }}</li>
        @endif
        <li>
            Business hours:
            @if (! empty($contact['hours']))
                {{ $contact['hours'] }}
            @else
                <span class="italic text-gray-400">[TODO: owner to confirm business hours]</span>
            @endif
        </li>
        <li><a href="{{ route('contacts.index') }}" class="font-semibold text-pink-600 hover:underline">Contact form</a></li>
    </ul>
</section>
