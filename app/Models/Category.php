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
        return $query->withCount('products')->having('products_count', '>', 0);
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

    // Query Scope
    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
