<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    public const PAYMENT_STATUSES = ['unpaid', 'paid', 'refunded'];

    protected $fillable = [
        'order_number', 'user_id',
        'first_name', 'last_name', 'email', 'phone',
        'address', 'city', 'zip_code', 'notes',
        'subtotal', 'shipping', 'total',
        'status', 'payment_status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total'    => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /** Bootstrap badge classes (admin panel) */
    public function statusBadge(): string
    {
        return match ($this->status) {
            'processing' => 'bg-info text-dark',
            'shipped'    => 'bg-primary',
            'delivered'  => 'bg-success',
            'cancelled'  => 'bg-danger',
            default      => 'bg-warning text-dark',
        };
    }

    public function paymentBadge(): string
    {
        return match ($this->payment_status) {
            'paid'     => 'bg-success',
            'refunded' => 'bg-dark',
            default    => 'bg-secondary',
        };
    }

    public static function generateNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('ymd') . '-' . Str::upper(Str::random(5));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }
}
