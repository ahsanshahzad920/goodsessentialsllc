@php
    $items = [
        ['truck',   'Free Shipping', 'On all orders'],
        ['shield',  'Safe Payments', '100% secure checkout'],
        ['refresh', 'Easy Returns',  '30 days return policy'],
        ['headset', '24/7 Support',  "We're here to help"],
    ];
@endphp

<section class="rounded-3xl bg-brand-500 p-2 text-white" aria-label="Store promises">
    <ul class="grid gap-4 rounded-[1.25rem] border border-dashed border-white/40 px-5 py-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-0">
        @foreach ($items as $i => [$icon, $title, $text])
            <li class="flex items-center gap-3 lg:px-5 {{ $i > 0 ? 'lg:border-l lg:border-white/25' : 'lg:pl-2' }}">
                <x-shop.icon :name="$icon" class="size-7 shrink-0" />
                <div>
                    <p class="text-sm font-extrabold leading-tight">{{ $title }}</p>
                    <p class="text-[11px] text-white/80">{{ $text }}</p>
                </div>
            </li>
        @endforeach
    </ul>
</section>
