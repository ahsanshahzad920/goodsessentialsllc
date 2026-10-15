<?php

if (!function_exists('calculateDiscountPrice')) {
    function calculateDiscountPrice($originalPrice, $discountPercent)
    {
        // Ensure discount percentage is valid
        if ($discountPercent < 0 || $discountPercent > 100) {
            return $originalPrice; // No discount
        }

        // Calculate the discount amount
        $discountAmount = ($originalPrice * $discountPercent) / 100;

        // Calculate the final price after discount
        $discountedPrice = $originalPrice - $discountAmount;

        return $discountedPrice;
    }
}

if (!function_exists('money')) {
    /**
     * Format an amount using the currency set in config/shop.php.
     * money(1299) => "Rs. 1,299.00"
     */
    function money($amount): string
    {
        $c = config('shop.currency', [
            'symbol' => '$', 'position' => 'before', 'decimals' => 2, 'space' => false,
        ]);

        $number = number_format((float) str_replace(',', '', (string) $amount), $c['decimals'], '.', ',');
        $gap    = ($c['space'] ?? true) ? ' ' : '';

        return ($c['position'] ?? 'before') === 'after'
            ? $number . $gap . $c['symbol']
            : $c['symbol'] . $gap . $number;
    }
}