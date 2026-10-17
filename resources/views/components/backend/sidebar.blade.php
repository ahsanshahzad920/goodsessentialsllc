@php
    $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
    $image = config('shop.logo_image');
    $name  = config('shop.name', config('app.name'));
@endphp

<div class="sidebar pb-3">
    <nav class="navbar navbar-light">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand mx-4 mb-3">
            <img src="{{ asset($image) }}" alt="" class="img-fluid w-100" />
        </a>

        <div class="navbar-nav w-100">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa fa-tachometer-alt me-2"></i>
                Dashboard
            </a>

            {{-- Orders --}}
            <a href="{{ route('orders.index') }}"
                class="nav-item nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <i class="fa fa-receipt me-2"></i>
                Orders
                @if ($pendingOrders > 0)
                    <span class="badge bg-warning text-dark ms-2">{{ $pendingOrders }}</span>
                @endif
            </a>

            {{-- Category --}}
            <a href="{{ route('categories.index') }}"
                class="nav-item nav-link {{ request()->routeIs('categories.index', 'categories.create', 'categories.edit') ? 'active' : '' }}">
                <i class="fa fa-tags me-2"></i>
                Categories
            </a>

            {{-- Product --}}
            <a href="{{ route('products.index') }}"
                class="nav-item nav-link {{ request()->routeIs('products.index', 'products.create', 'products.edit') ? 'active' : '' }}">
                <i class="fa fa-shopping-bag me-2"></i>
                Products
            </a>
        </div>
    </nav>
</div>