<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_title',
        'product_description',
        'product_image',
        'category_id',
        'created_by',
        'updated_by',
        'price',
        'dis_price',
        'slug',
        'sku',
        'meta_title',
        'meta_description',
        'og_image',
        'brand',
        'color',
        'size',
        'material',
        'suggested_min_age_months',
        'suggested_max_age_months',
        'colors',
    ];
    protected $casts = ['colors' => 'array'];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function isInWishlist()
    // {
    //     if (Auth::check()) {
    //         return Wishlist::where('user_id', Auth::id())
    //             ->where('product_id', $this->id)
    //             ->exists();
    //     }
    //     return false;
    // }

    // Query Scope
    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Text search over title, SKU and description, as ONE grouped condition
     * so it combines correctly with category filters (the old shop query
     * chained bare orWhere()s, which let other categories leak in).
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }
        $like = '%' . addcslashes($term, '%_\\') . '%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('product_title', 'LIKE', $like)
              ->orWhere('sku', 'LIKE', $like)
              ->orWhere('product_description', 'LIKE', $like);
        });
    }

    /** Products shown in homepage baby sections (excludes e.g. Mom & Wellness). */
    public function scopeForBabySections(Builder $query): Builder
    {
        $excluded = config('shop.homepage_exclude_categories', []);

        return $excluded
            ? $query->whereDoesntHave('category', fn ($c) => $c->whereIn('slug', $excluded))
            : $query;
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }
}
