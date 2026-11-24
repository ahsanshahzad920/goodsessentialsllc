<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

/** Data for the slide-in cart: lines, totals, free-delivery progress and add-ons. */
class CartDrawer
{
    public static function data(): array
    {
        $cart = session('cart', []);
        $lines = collect($cart)->map(fn ($l, $key) => $l + ['key' => (string) $key])->values();
        $subtotal = (float) $lines->sum(fn ($l) => $l['quantity'] * $l['product_price']);

        $threshold = Settings::freeDeliveryThreshold();

        return [
            'lines'     => $lines,
            'count'     => (int) $lines->sum('quantity'),
            'subtotal'  => $subtotal,
            'threshold' => $threshold,
            'remaining' => Shipping::remainingForFree($subtotal),
            'progress'  => $threshold ? min(100, (int) round($subtotal / $threshold * 100)) : null,
            'addons'    => $lines->isEmpty() ? collect() : self::addons($lines->pluck('product_id')->all()),
        ];
    }

    /**
     * Small one-tap add-ons: the lowest-priced baby products that can be added
     * without choosing anything (0/1 colour), are in stock and not in the cart.
     */
    public static function addons(array $inCart, int $limit = 3): Collection
    {
        $final = "CAST(price AS DECIMAL(12,2)) * (1 - COALESCE(CAST(NULLIF(dis_price, '') AS DECIMAL(6,2)), 0) / 100)";

        return Product::forBabySections()
            ->withCardData()
            ->whereNotIn('id', $inCart)
            ->where(fn ($q) => $q->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0))
            ->reorder()->orderByRaw("$final ASC")
            ->take(12)->get()
            ->filter(fn ($p) => count($p->colors ?? []) <= 1)
            ->take($limit)
            ->values();
    }
}
