<?php

namespace App\Support;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Optional per-product inventory (products.stock_quantity; NULL = untracked).
 * Only real numbers entered by the owner ever reach the storefront.
 */
class Stock
{
    /** "Only N left" is shown at or below this many units. */
    public const LOW = 5;

    public static function tracked(Product $p): bool
    {
        return $p->stock_quantity !== null;
    }

    public static function soldOut(Product $p): bool
    {
        return self::tracked($p) && (int) $p->stock_quantity <= 0;
    }

    /** Units left when low (1..LOW), else null. */
    public static function lowCount(Product $p): ?int
    {
        return self::tracked($p) && $p->stock_quantity > 0 && $p->stock_quantity <= self::LOW
            ? (int) $p->stock_quantity
            : null;
    }

    /** How many more of this product may go into the cart, or null if untracked. */
    public static function available(Product $p, int $alreadyInCart = 0): ?int
    {
        return self::tracked($p) ? max(0, (int) $p->stock_quantity - $alreadyInCart) : null;
    }

    /**
     * Inside the checkout transaction: lock tracked products, make sure every
     * line still fits, then decrement. Throws a ValidationException (shown on
     * the checkout page) when something sold out meanwhile.
     *
     * @param array<int, array{product: Product, quantity: int}> $lines
     */
    public static function reserve(array $lines): void
    {
        $need = [];
        foreach ($lines as $line) {
            $need[$line['product']->id] = ($need[$line['product']->id] ?? 0) + (int) $line['quantity'];
        }

        $rows = Product::withoutGlobalScopes()->whereIn('id', array_keys($need))
            ->whereNotNull('stock_quantity')->lockForUpdate()->get()->keyBy('id');

        foreach ($rows as $id => $p) {
            if ($p->stock_quantity < $need[$id]) {
                throw ValidationException::withMessages([
                    'stock' => $p->stock_quantity > 0
                        ? "Only {$p->stock_quantity} of \"{$p->product_title}\" left. Please lower the quantity in your cart."
                        : "\"{$p->product_title}\" just sold out. Please remove it from your cart.",
                ]);
            }
        }

        foreach ($rows as $id => $p) {
            DB::table('products')->where('id', $id)->decrement('stock_quantity', $need[$id]);
        }
    }

    /** Put an order's units back (order cancelled) or take them again (un-cancelled). */
    public static function adjustForOrder(Order $order, int $direction): void
    {
        foreach ($order->items as $item) {
            if (! $item->product_id) {
                continue;
            }
            $q = DB::table('products')->where('id', $item->product_id)->whereNotNull('stock_quantity');
            $direction > 0
                ? $q->increment('stock_quantity', $item->quantity)
                : $q->update(['stock_quantity' => DB::raw('GREATEST(CAST(stock_quantity AS SIGNED) - ' . (int) $item->quantity . ', 0)')]);
        }
    }
}
