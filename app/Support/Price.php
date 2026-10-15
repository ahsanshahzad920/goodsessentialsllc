<?php

namespace App\Support;

use App\Models\Product;

/**
 * Single source of truth for a product's selling price,
 * so cart, checkout and order always agree with what the shop displays.
 */
final class Price
{
    public static function final(Product $product): float
    {
        $value = calculateDiscountPrice($product->price, $product->dis_price) ?? $product->price ?? 0;

        return round((float) str_replace(',', '', (string) $value), 2);
    }
}
