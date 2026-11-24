<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'created_by',
        'updated_by',
        'parent_id',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    // Relations

    // Define a relationship to get subcategories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Define a relationship to get the parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Categories fit to show in nav, footer, and other category menus/widgets:
     * only ones with at least one product. There is no separate "active" or
     * "in stock" flag on products today, so having a product row is the only
     * signal available; this hides exactly the empty categories (Baby Clothing,
     * Newborn, Winter Suits & Fleece Rompers) without deleting them.
     */
    public function scopeWithProducts(Builder $query): Builder
    {
        return $query->whereHas('products')->withCount('products');
    }

    /**
     * Single, cached source for nav / footer / homepage category widgets, so
     * a newly-emptied or newly-stocked category shows up consistently in all
     * three places within a few minutes instead of each rendering its own
     * uncached query. Cleared automatically every 5 minutes, or immediately
     * via `php artisan cache:clear` after adding products to an empty category.
     *
     * @return \Illuminate\Support\Collection<int, array{name:string, slug:string, label:string, icon:string, tone:string}>
     */
    public static function navItems(): \Illuminate\Support\Collection
    {
        return Cache::remember('shop.visible_categories', 300, function () {
            $configured = collect(config('shop.categories', []))
                ->keyBy(fn ($c) => strtolower($c['name']));

            return static::withProducts()->orderBy('name')->get()
                ->map(function (self $category) use ($configured) {
                    $settings = $configured->get(strtolower($category->name), []);

                    return [
                        'name'  => $category->name,
                        'slug'  => $category->slug,
                        'label' => $settings['label'] ?? $category->name,
                        'icon'  => $settings['icon'] ?? 'tag',
                        'tone'  => $settings['tone'] ?? 'lavender',
                    ];
                })
                ->values();
        });
    }

    /**
     * Homepage collection tiles, built from real categories (the old tiles
     * came from config/shop.php and pointed at categories that don't exist,
     * with stock textures as images). Image priority: admin-uploaded
     * categories.image -> best-selling product in the category (by units
     * ordered) -> newest product with an image. Non-baby categories listed in
     * shop.homepage_exclude_categories are skipped.
     *
     * @return \Illuminate\Support\Collection<int, array{name:string, slug:string, label:string, icon:string, tone:string, text:?string, image:?string, count:int}>
     */
    public static function homeTiles(int $limit = 4): \Illuminate\Support\Collection
    {
        return Cache::remember('shop.home_tiles', 300, function () use ($limit) {
            $configured = collect(config('shop.categories', []))->keyBy(fn ($c) => strtolower($c['name']));
            $excluded = config('shop.homepage_exclude_categories', []);

            return static::withoutGlobalScope('latest')->withProducts()
                ->whereNotIn('slug', $excluded)
                ->orderByDesc('products_count')->orderBy('name')
                ->take($limit)->get()
                ->map(function (self $category) use ($configured) {
                    $settings = $configured->get(strtolower($category->name), []);

                    $image = $category->image ?: static::bestSellersIn($category->id, 1)->value('product_image');


                    return [
                        'name'  => $category->name,
                        'slug'  => $category->slug,
                        'label' => $settings['label'] ?? $category->name,
                        'icon'  => $settings['icon'] ?? 'tag',
                        'tone'  => $settings['tone'] ?? 'lavender',
                        'text'  => $settings['text'] ?? null,
                        // 96px bubble at up to 2.6x density.
                        'image' => $image ? \App\Support\WebpImage::fit($image, 240) : null,
                        'count' => (int) $category->products_count,
                    ];
                })
                ->values();
        });
    }

    /**
     * Products of one category ranked by units actually ordered, newest first
     * among equals — "best sellers" from real order data.
     */
    public static function bestSellersIn(int $categoryId, int $limit): \Illuminate\Database\Eloquent\Builder
    {
        return Product::withoutGlobalScopes()
            ->where('products.category_id', $categoryId)
            ->whereNotNull('products.product_image')
            // Correlated subquery instead of JOIN + GROUP BY: safe under
            // ONLY_FULL_GROUP_BY on both MySQL and MariaDB.
            ->orderByRaw('(SELECT COALESCE(SUM(oi.quantity), 0) FROM order_items oi WHERE oi.product_id = products.id) DESC')
            ->orderByDesc('products.created_at')
            ->take($limit);
    }

    /**
     * Desktop mega-menu: every nav category with its image and top 4 products.
     *
     * @return \Illuminate\Support\Collection<int, array>
     */
    public static function megaMenu(): \Illuminate\Support\Collection
    {
        return Cache::remember('shop.mega_menu', 300, function () {
            $rows = static::withoutGlobalScope('latest')->withProducts()->get()->keyBy('slug');

            return static::navItems()->map(function (array $nav) use ($rows) {
                $category = $rows->get($nav['slug']);
                if (! $category) {
                    return null;
                }
                $products = static::bestSellersIn($category->id, 4)->get();
                $image = $category->image ?: optional($products->first())->product_image;

                return $nav + [
                    'count'    => (int) $category->products_count,
                    'image'    => $image ? \App\Support\WebpImage::fit($image, 480) : null,
                    'products' => $products->map(function (Product $p) {
                        $price = (float) str_replace(',', '', (string) $p->price);
                        $final = (float) calculateDiscountPrice($price, (float) $p->dis_price);
                        return [
                            'title' => $p->product_title,
                            'url'   => route('productDetails', $p->slug),
                            'image' => \App\Support\WebpImage::fit($p->product_image, 240),
                            'price' => $final ?: $price,
                            'was'   => $final > 0 && $final < $price ? $price : null,
                        ];
                    })->all(),
                ];
            })->filter()->values();
        });
    }

    public static function forgetCaches(): void
    {
        Cache::forget('shop.visible_categories');
        Cache::forget('shop.home_tiles');
        Cache::forget('shop.mega_menu');
    }

    // Query Scope
    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
