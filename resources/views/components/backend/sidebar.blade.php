<div class="sidebar pb-3">
    <nav class="navbar navbar-light">
        <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3">
            {{-- <img src="dashasset/img/logo.png" alt="" class="img-fluid" /> --}}
            <img src="{{ asset('dashasset/img/SVG-NavBar-Logo.svg') }}" alt="" class="img-fluid w-100" />
        </a>

        <div class="navbar-nav w-100">
            <a href="{{ route('dashboard') }}"
                class="nav-item nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa fa-tachometer-alt me-2"></i>
                Dashboard
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
