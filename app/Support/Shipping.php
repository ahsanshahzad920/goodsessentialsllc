<?php

namespace App\Support;

/**
 * Single place that decides the delivery charge, used by checkout (server
 * total), the checkout page JS (via data attributes) and the cart progress
 * copy — so what the announcement bar promises is what checkout charges.
 */
class Shipping
{
    public static function baseFor(?string $paymentMethod): float
    {
        $key = $paymentMethod === 'card' ? 'shop.shipping_online_payment' : 'shop.shipping_cod_payment';

        return (float) config($key, 50);
    }

    public static function for(float $subtotal, ?string $paymentMethod): float
    {
        if ($subtotal <= 0) {
            return 0.0;
        }

        return self::qualifiesForFree($subtotal) ? 0.0 : self::baseFor($paymentMethod);
    }

    public static function qualifiesForFree(float $subtotal): bool
    {
        $threshold = Settings::freeDeliveryThreshold();

        return $threshold !== null && $subtotal >= $threshold;
    }

    /** Rupees still needed for free delivery, or null when the offer is off / already reached. */
    public static function remainingForFree(float $subtotal): ?float
    {
        $threshold = Settings::freeDeliveryThreshold();
        if ($threshold === null || $subtotal >= $threshold) {
            return null;
        }

        return round($threshold - $subtotal, 2);
    }
}
