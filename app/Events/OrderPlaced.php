<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;

/** Fired once after a checkout order is saved (COD and card). */
class OrderPlaced
{
    use Dispatchable;

    public function __construct(public Order $order)
    {
    }
}
