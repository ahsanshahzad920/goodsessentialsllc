@php
    $image = config('shop.logo_image');
    $name  = config('shop.name', config('app.name'));
@endphp
<nav class="navbar navbar-expand bg-white border-bottom navbar-light sticky-top px-4 py-0">

    {{-- Mobile Logo --}}
    <a href="{{ url('/admin') }}" class="navbar-brand d-flex d-lg-none me-4">
        <img src="{{ asset($image) }}"
             alt="{{ $name }}"
             class="img-fluid"
             style="max-height: 45px;">
    </a>

    {{-- Sidebar Toggle --}}
    <a href="#" class="sidebar-toggler flex-shrink-0 text-dark text-decoration-none">
        <i class="fa fa-bars"></i>
    </a>

    <div class="navbar-nav align-items-center ms-auto">

        {{-- Notifications --}}
        <div class="nav-item dropdown">
            <a href="#" class="nav-link" data-bs-toggle="dropdown" aria-label="Notifications">
                <i class="fa fa-bell me-lg-2 bg-dark text-white p-2 rounded"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <h6 class="dropdown-header">Notifications</h6>

                <div class="dropdown-item text-muted small">
                    No new notifications
                </div>
            </div>
        </div>

        {{-- Logged-in User --}}
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">

                @php
                    $user = auth()->user();
                    $profileImage = $user?->image;
                @endphp

                @if ($profileImage)
                    <img
                        class="rounded-circle me-lg-2"
                        src="{{ asset('storage/' . $profileImage) }}"
                        alt="{{ $user->name }}"
                        style="width: 40px; height: 40px; object-fit: cover;"
                    >
                @else
                    <img
                        class="rounded-circle me-lg-2"
                        src="{{ asset('dashasset/img/user.jpg') }}"
                        alt="Default profile image"
                        style="width: 40px; height: 40px; object-fit: cover;"
                    >
                @endif

                <span class="d-none d-lg-inline-flex">
                    {{ $user?->name ?? 'Guest' }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">

                <a href="{{ route('profile') }}" class="dropdown-item">
                    <i class="fa fa-user me-2"></i> My Profile
                </a>

                <!-- <a href="#" class="dropdown-item">
                    <i class="fa fa-cog me-2"></i> Settings
                </a> -->

                <hr class="dropdown-divider">

                <x-forms.form action="{{ route('logout') }}" method="POST">
                    <button type="submit" class="dropdown-item">
                        <i class="fa fa-sign-out-alt me-2"></i> Log Out
                    </button>
                </x-forms.form>

            </div>
        </div>
    </div>
</nav>