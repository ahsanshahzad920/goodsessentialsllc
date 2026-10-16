<?php

namespace App\Http\Controllers\Backend\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = in_array($request->status, Order::STATUSES, true) ? $request->status : null;
        $search = trim((string) $request->q);

        $orders = Order::query()
            ->withCount('items')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('order_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('backend.orders.index', [
            'orders'   => $orders,
            'counts'   => $counts,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items', 'user');

        return view('backend.orders.show', [
            'order'           => $order,
            'statuses'        => Order::STATUSES,
            'paymentStatuses' => Order::PAYMENT_STATUSES,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'         => ['required', Rule::in(Order::STATUSES)],
            'payment_status' => ['required', Rule::in(Order::PAYMENT_STATUSES)],
        ]);
        
        if ($data['payment_status'] === 'paid' && ! $order->paid_at) {
            $data['paid_at'] = now();
        }

        $order->update($data);

        return redirect()->route('orders.show', $order)->with('success', "Order {$order->order_number} updated.");
    }
}
