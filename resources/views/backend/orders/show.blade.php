@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h3 class="heading mb-1">Order {{ $order->order_number }}</h3>
                <div class="text-muted small">Placed {{ $order->created_at->format('M j, Y g:i A') }}</div>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to orders</a>
        </div>

        <div class="row g-4 mt-1">

            {{-- Items + totals --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Items</h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if ($item->product_image)
                                                        <img src="{{ asset('storage/' . $item->product_image) }}" alt="" width="50" height="50" style="object-fit: cover; border-radius: 6px;">
                                                    @endif
                                                    <span>{{ $item->product_title }}</span>
                                                </div>
                                            </td>
                                            <td>{{ money($item->price) }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ money($item->total) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="ms-auto mt-3" style="max-width: 320px;">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Subtotal</span>
                                <span>{{ money($order->subtotal) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Shipping</span>
                                <span>{{ money($order->shipping) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total</span>
                                <span>{{ money($order->total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Update order</h5>
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3 fw-bold">
                                <label for="status">Order status</label>
                                <select name="status" id="status" class="form-select">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3 fw-bold">
                                <label for="payment_status">Payment status</label>
                                <select name="payment_status" id="payment_status" class="form-select">
                                    @foreach ($paymentStatuses as $paymentStatus)
                                        <option value="{{ $paymentStatus }}" @selected(old('payment_status', $order->payment_status) === $paymentStatus)>{{ ucfirst($paymentStatus) }}</option>
                                    @endforeach
                                </select>
                                @error('payment_status') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-success w-100">Update Order</button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Customer</h5>
                        <p class="mb-1 fw-semibold">{{ $order->full_name }}</p>
                        <p class="mb-1"><a href="mailto:{{ $order->email }}">{{ $order->email }}</a></p>
                        <p class="mb-2">{{ $order->phone }}</p>
                        @if ($order->user_id)
                            <span class="badge bg-success">Registered account</span>
                        @else
                            <span class="badge bg-light text-dark border">Guest checkout</span>
                        @endif
                        <p class="small text-muted mt-2 mb-0">
                            IP address:
                            @if ($order->ip_address)
                                <code>{{ $order->ip_address }}</code>
                                <a href="https://ipinfo.io/{{ $order->ip_address }}" target="_blank" rel="noopener noreferrer">Look up location</a>
                            @else
                                N/A
                            @endif
                        </p>

                        <hr>
                        <h6>Delivery address</h6>
                        <address class="mb-0">
                            {{ $order->address }}<br>
                            {{ $order->city }} {{ $order->zip_code }}
                        </address>

                        @if ($order->notes)
                            <hr>
                            <h6>Customer notes</h6>
                            <p class="mb-0">{{ $order->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
