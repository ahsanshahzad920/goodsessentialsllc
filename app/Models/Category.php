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

                    $image = $category->image;
                    if (! $image) {
                        $image = Product::withoutGlobalScopes()
                            ->where('products.category_id', $category->id)
                            ->whereNotNull('products.product_image')
                            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
                            ->groupBy('products.id', 'products.product_image', 'products.created_at')
                            ->orderByRaw('COALESCE(SUM(order_items.quantity), 0) DESC')
                            ->orderByDesc('products.created_at')
                            ->value('products.product_image');
                    }

                    return [
                        'name'  => $category->name,
                        'slug'  => $category->slug,
                        'label' => $settings['label'] ?? $category->name,
                        'icon'  => $settings['icon'] ?? 'tag',
                        'tone'  => $settings['tone'] ?? 'lavender',
                        'text'  => $settings['text'] ?? null,
                        'image' => $image ? asset('storage/' . $image) : null,
                        'count' => (int) $category->products_count,
                    ];
                })
                ->values();
        });
    }

    public static function forgetCaches(): void
    {
        Cache::forget('shop.visible_categories');
        Cache::forget('shop.home_tiles');
    }

    // Query Scope
    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
