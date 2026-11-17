<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCheckout extends Model
{
    protected $fillable = ['session_id', 'phone', 'name', 'city', 'items', 'subtotal', 'order_id', 'contacted_at'];

    protected $casts = ['items' => 'array', 'contacted_at' => 'datetime', 'subtotal' => 'float'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /** After an order is placed: close any open rows for this session or phone. */
    public static function markConverted(Order $order, ?string $sessionId): void
    {
        rescue(function () use ($order, $sessionId) {
            static::whereNull('order_id')
                ->where(fn ($q) => $q->where('phone', $order->phone)->when($sessionId, fn ($q2) => $q2->orWhere('session_id', $sessionId)))
                ->update(['order_id' => $order->id, 'updated_at' => now()]);
        }, report: false);
    }
}
