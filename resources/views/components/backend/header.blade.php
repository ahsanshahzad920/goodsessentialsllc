<nav class="navbar navbar-expand bg-white border-bottom navbar-light sticky-top px-4 py-0">
    <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
        <!-- <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2> -->
        <img src="dashasset/img/logo.png" alt="" class="img-fluid" />
    </a>
    <a href="#" class="sidebar-toggler flex-shrink-0 text-dark text-decoration-none">
        <i class="fa fa-bars"></i>
    </a>
    <div class="navbar-nav align-items-center ms-auto">
        <div class="nav-item dropdown">
            <a href="#" class="nav-link" data-bs-toggle="dropdown">
                <i class="fa fa-bell me-lg-2 bg-dark text-white"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a href="#" class="dropdown-item">
                    <h6 class="fw-normal mb-0">Profile updated</h6>
                    <small>15 minutes ago</small>
                </a>
                <hr class="dropdown-divider" />
                <a href="#" class="dropdown-item">
                    <h6 class="fw-normal mb-0">New user added</h6>
                    <small>15 minutes ago</small>
                </a>
                <hr class="dropdown-divider" />
                <a href="#" class="dropdown-item">
                    <h6 class="fw-normal mb-0">Password changed</h6>
                    <small>15 minutes ago</small>
                </a>
                <hr class="dropdown-divider" />
                <a href="#" class="dropdown-item text-center">See all notifications</a>
            </div>
        </div>
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img class="rounded-circle me-lg-2" src="dashasset/img/user.jpg" alt=""
                    style="width: 40px; height: 40px" />
                <span class="d-none d-lg-inline-flex">John Doe</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a href="{{ route('profile') }}" class="dropdown-item">My Profile</a>
                <a href="#" class="dropdown-item">Settings</a>
                <x-forms.form action="{{ route('logout') }}" method="POST">
                    <button class="dropdown-item">Log Out</button>
                </x-forms.form>
            </div>
        </div>
    </div>
</nav>
