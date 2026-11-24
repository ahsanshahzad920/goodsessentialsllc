<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Review extends Model
{
    protected $fillable = [
        'product_id', 'order_id', 'name', 'city', 'rating', 'body', 'photo', 'is_featured', 'is_approved', 'is_verified',
    ];

    protected $casts = [
        'rating'      => 'integer',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('is_approved', true);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    /** Raw account names like "Guest-amir665" -> "Amir". */
    protected function cleanName(): string
    {
        $name = preg_replace('/^guest[-_ ]*/i', '', trim($this->name));
        $name = trim(preg_replace('/[\d_]+/', ' ', $name));

        return $name !== '' ? Str::title($name) : 'Customer';
    }

    /** First name only, for public display. */
    public function getDisplayNameAttribute(): string
    {
        return Str::before($this->cleanName(), ' ');
    }

    /** Initials derived from the real name: "Ayesha Khan" -> "AK", "Amir" -> "A". */
    public function getInitialsAttribute(): string
    {
        $words = array_slice(preg_split('/\s+/', $this->cleanName()), 0, 2);

        return Str::upper(implode('', array_map(fn ($w) => mb_substr($w, 0, 1), $words)));
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
}
