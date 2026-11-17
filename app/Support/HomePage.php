<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Data for the homepage sections. Every number shown to shoppers comes from
 * real rows (orders, products, reviews). Sections without enough real data
 * return empty and are hidden — nothing is padded or invented.
 */
class HomePage
{
    /** Minimum products with real sales before "Best Sellers" is shown. */
    public const BEST_SELLERS_MIN = 4;

    /** Minimum units sold in 7 days before "X sold this week" is shown on a deal. */
    public const SOLD_BADGE_MIN = 3;

    /** @return Collection<int, array> up to 3 hero slides */
    public static function heroSlides(): Collection
    {
        $configured = collect(Settings::json('hero_slides'))
            ->filter(fn ($s) => ! empty($s['product_id']))
            ->take(3);

        $slides = $configured->isNotEmpty()
            ? $configured->map(fn ($s) => [Product::find($s['product_id']), $s])
            : self::defaultSlides();

        return $slides
            ->filter(fn ($pair) => $pair[0] instanceof Product)
            ->map(fn ($pair) => self::slide($pair[0], $pair[1]))
            ->values();
    }

    /**
     * Defaults until the owner picks slides in Store Settings: the product the
     * old static banner promoted, the most-discounted winter item, and a gift
     * set. Uses whatever of these exists; never invents a product.
     */
    private static function defaultSlides(): Collection
    {
        $hero = Product::where('slug', 'sw-6-character-baby-wrapping-swaddle')->first();

        $winter = Product::whereHas('category', fn ($c) => $c->where('slug', 'winter-suits-fleece-rompers'))
            ->reorder()->orderByRaw('CAST(dis_price AS DECIMAL(6,2)) DESC')->latest()->first();

        $gift = Product::forBabySections()->where('product_title', 'LIKE', '%gift%')->first();

        return collect([
            [$hero, ['eyebrow' => 'Bestselling swaddles']],
            [$winter, ['eyebrow' => 'Winter collection']],
            [$gift, ['eyebrow' => 'Gift sets']],
        ])->filter(fn ($p) => $p[0])->unique(fn ($p) => $p[0]->id)->values();
    }

    private static function slide(Product $p, array $s): array
    {
        $price = (float) str_replace(',', '', (string) $p->price);
        $final = (float) calculateDiscountPrice($price, (float) $p->dis_price);
        $pay = $final ?: $price;
        $off = $final > 0 && $final < $price ? (int) round((1 - $final / $price) * 100) : 0;
        $colors = $p->colors ?? [];

        // A slide is only as large as its first gallery image (cover first).
        $image = $p->images()->first()?->path ?? $p->product_image;

        return [
            'product'   => $p,
            'eyebrow'   => trim($s['eyebrow'] ?? '') ?: ($p->category->name ?? null),
            'badge'     => trim($s['badge'] ?? '') ?: ($off ? $off . '% OFF' : null),
            'headline'  => trim($s['headline'] ?? '') ?: $p->product_title,
            'price'     => $pay,
            'was'       => $off ? $price : null,
            'url'       => route('productDetails', $p->slug),
            'image'     => $image ? asset('storage/' . $image) : asset('images/product-placeholder.svg'),
            'webp'      => WebpImage::url($image),
            // One-tap COD order when there is nothing to choose (0 or 1 colour).
            'quick_buy' => count($colors) <= 1,
            'color'     => count($colors) === 1 ? $colors[0]['hex'] : null,
        ];
    }

    /** Deals of the week: discounted baby products + optional real end time. */
    public static function deals(): array
    {
        $endsAt = Settings::get('deals_ends_at');
        $endsAt = $endsAt ? rescue(fn () => Carbon::parse($endsAt, config('app.timezone')), null, false) : null;

        // An admin-set end time that has passed hides the section entirely.
        if ($endsAt && $endsAt->isPast()) {
            return ['products' => collect(), 'ends_at' => null, 'sold' => []];
        }

        $products = Product::forBabySections()->withCardData()
            ->where('dis_price', '>', 0)
            ->reorder()->orderByRaw('CAST(dis_price AS DECIMAL(6,2)) DESC')->latest()
            ->take(8)->get();

        return ['products' => $products, 'ends_at' => $endsAt, 'sold' => self::soldThisWeek($products->pluck('id')->all())];
    }

    /** product_id => units ordered in the last 7 days (only >= SOLD_BADGE_MIN). */
    public static function soldThisWeek(array $ids): array
    {
        if (! $ids) {
            return [];
        }

        return DB::table('order_items')
            ->selectRaw('product_id, SUM(quantity) AS units')
            ->whereIn('product_id', $ids)
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('product_id')
            ->havingRaw('SUM(quantity) >= ?', [self::SOLD_BADGE_MIN])
            ->pluck('units', 'product_id')
            ->map(fn ($v) => (int) $v)
            ->all();
    }

    /** Best sellers by real units ordered; empty until enough products have sold. */
    public static function bestSellers(int $limit = 8): Collection
    {
        return Cache::remember('home.best_sellers', 300, function () use ($limit) {
            $products = Product::forBabySections()->withCardData()
                ->reorder()
                ->selectSub(
                    DB::table('order_items')->selectRaw('COALESCE(SUM(quantity), 0)')->whereColumn('order_items.product_id', 'products.id'),
                    'units_sold'
                )
                ->orderByDesc('units_sold')->latest()
                ->take($limit)->get()
                ->filter(fn ($p) => $p->units_sold > 0)
                ->values();

            return $products->count() >= self::BEST_SELLERS_MIN ? $products : collect();
        });
    }

    public static function newArrivals(int $limit = 8): Collection
    {
        return Product::forBabySections()->withCardData()->take($limit)->get();
    }

    /** Age buckets that actually contain products: key => [label, count]. */
    public static function ageBuckets(): array
    {
        return Cache::remember('home.age_buckets', 300, function () {
            $out = [];
            foreach (AgeRange::BUCKETS as $key => [$label]) {
                $n = Product::forBabySections()->forAge($key)->count();
                if ($n > 0) {
                    $out[$key] = [$label, $n];
                }
            }

            return $out;
        });
    }

    /** Gift sets for the gift section (titles or categories that say "gift"). */
    public static function giftProducts(int $limit = 4): Collection
    {
        return Product::forBabySections()
            ->where(fn ($q) => $q->where('product_title', 'LIKE', '%gift%')
                ->orWhereHas('category', fn ($c) => $c->where('name', 'LIKE', '%gift%')))
            ->take($limit)->get();
    }

    public static function categoryBubbles(): Collection
    {
        return Category::megaMenu();
    }
}
