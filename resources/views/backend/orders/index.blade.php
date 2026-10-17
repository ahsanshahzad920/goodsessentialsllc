@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h3 class="heading mb-0">Orders</h3>

            <form method="GET" action="{{ route('orders.index') }}" class="d-flex gap-2">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="search" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="Search order #, name, email, phone" style="min-width: 280px;">
                <button class="btn btn-primary" type="submit">Search</button>
                @if (request()->hasAny(['q', 'status']))
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </form>
        </div>

        {{-- Status filters --}}
        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{ route('orders.index', array_filter(['q' => request('q')])) }}"
                class="btn btn-sm {{ request('status') ? 'btn-outline-dark' : 'btn-dark' }}">
                All <span class="badge bg-light text-dark">{{ $counts->sum() }}</span>
            </a>
            @foreach ($statuses as $status)
                <a href="{{ route('orders.index', array_filter(['status' => $status, 'q' => request('q')])) }}"
                    class="btn btn-sm {{ request('status') === $status ? 'btn-dark' : 'btn-outline-dark' }}">
                    {{ ucfirst($status) }} <span class="badge bg-light text-dark">{{ $counts[$status] ?? 0 }}</span>
                </a>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body">
                @if ($orders->isEmpty())
                    <div class="alert alert-info text-center mb-0">No orders found.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th class="text-center">Items</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Placed</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="fw-bold">{{ $order->order_number }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $order->full_name }}</div>
                                            <div class="small text-muted">{{ $order->email }}</div>
                                            @if ($order->user_id)
                                                <span class="badge bg-success">Registered</span>
                                            @else
                                                <span class="badge bg-light text-dark border">Guest</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $order->items_count }}</td>
                                        <td>{{ money($order->total) }}</td>
                                        <td>
                                            <span class="badge {{ $order->paymentBadge() }}">{{ ucfirst($order->payment_status) }}</span>
                                            <div class="small text-muted">{{ $order->payment_method_label }}</div>
                                        </td>
                                        <td><span class="badge {{ $order->statusBadge() }}">{{ ucfirst($order->status) }}</span></td>
                                        <td class="small">{{ $order->created_at->format('M j, Y g:i A') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-warning">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
