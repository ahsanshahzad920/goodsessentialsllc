<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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
    // Query Scope
    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
