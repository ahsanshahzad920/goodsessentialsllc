{{--
    Reusable product-page trust block: delivery, COD, and returns. Single
    source of truth (config/shop.php delivery message) so this can never say
    something different from the Shipping Policy or homepage.
--}}
<div class="grid grid-cols-1 gap-3 border-t border-gray-100 pt-5 sm:grid-cols-3">
    <div class="flex items-start gap-2.5">
        <i class="fa-solid fa-truck mt-0.5 text-pink-500"></i>
        <div>
            <p class="text-sm font-bold text-gray-800">Delivery</p>
            <p class="text-xs text-gray-500">{{ config('shop.delivery.message_short') }}</p>
        </div>
    </div>
    <div class="flex items-start gap-2.5">
        <i class="fa-solid fa-money-bill-wave mt-0.5 text-pink-500"></i>
        <div>
            <p class="text-sm font-bold text-gray-800">Cash on Delivery</p>
            <p class="text-xs text-gray-500">Available for orders under Rs. 40,000</p>
        </div>
    </div>
    <div class="flex items-start gap-2.5">
        <i class="fa-solid fa-rotate-left mt-0.5 text-pink-500"></i>
        <div>
            <p class="text-sm font-bold text-gray-800">Returns</p>
            <p class="text-xs text-gray-500">
                <a href="{{ route('return-refund-policy') }}" class="underline hover:text-pink-600">14 days, unused &amp; original packaging</a>
            </p>
        </div>
    </div>
</div>
