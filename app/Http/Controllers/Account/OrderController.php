<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Customers can only ever see their own orders.
        abort_unless((int) $order->user_id === (int) Auth::id(), 404);

        $order->load('items');

        return view('account.orders.show', compact('order'));
    }
}
