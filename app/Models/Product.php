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

    /** Products whose suitable age range overlaps an AgeRange bucket ('0-3m', '1-3y', …). */
    public function scopeForAge(Builder $query, ?string $bucket): Builder
    {
        if (! \App\Support\AgeRange::valid($bucket)) {
            return $query;
        }
        [, $from, $to] = \App\Support\AgeRange::BUCKETS[$bucket];

        return $query->where(function (Builder $q) {
            $q->whereNotNull('suggested_min_age_months')->orWhereNotNull('suggested_max_age_months');
        })->where(function (Builder $q) use ($to) {
            $q->whereNull('suggested_min_age_months')->orWhere('suggested_min_age_months', '<', $to);
        })->where(function (Builder $q) use ($from) {
            $q->whereNull('suggested_max_age_months')->orWhere('suggested_max_age_months', '>', $from);
        });
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
