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
    ];

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
}
