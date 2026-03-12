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
