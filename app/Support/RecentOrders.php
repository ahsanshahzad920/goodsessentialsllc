<?php

namespace App\Support;

use App\Models\OrderItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * "Ayesha from Multan ordered … — 2 hours ago" social-proof toasts.
 * Only real, non-cancelled orders from the last 7 days; only the shopper's
 * first name and city are ever exposed. Off when the admin disables it.
 */
class RecentOrders
{
    public const DAYS = 7;

    public static function enabled(): bool
    {
        return Settings::bool('recent_orders_enabled');
    }

    public static function feed(int $limit = 5): array
    {
        if (! self::enabled()) {
            return [];
        }

        return Cache::remember('recent_orders_feed', 300, function () use ($limit) {
            $excluded = config('shop.homepage_exclude_categories', []);

            return OrderItem::query()
                ->with(['order:id,first_name,city,status,created_at', 'product:id,slug,product_image,category_id', 'product.category:id,slug'])
                ->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled')->where('created_at', '>=', now()->subDays(self::DAYS)))
                ->latest('id')->take(30)->get()
                ->filter(fn ($i) => $i->product && ! in_array($i->product->category->slug ?? '', $excluded, true))
                ->unique('order_id')
                ->take($limit)
                ->map(fn ($i) => [
                    'name'    => Str::title(Str::before(trim($i->order->first_name), ' ')) ?: 'A customer',
                    'city'    => Str::title(trim($i->order->city)),
                    'product' => $i->product_title,
                    'ago'     => $i->order->created_at->diffForHumans(),
                    'url'     => route('productDetails', $i->product->slug),
                    'image'   => WebpImage::fit($i->product->product_image, 240),
                ])
                ->values()->all();
        });
    }
}
