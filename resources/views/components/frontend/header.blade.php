    <header class="header_area sticky-header">
        <div class="main_menu">
            <nav class="navbar navbar-expand-lg navbar-light fixed-top main_box py-lg-0 py-3">
                <div class="container">
                    <a class="navbar-brand logo_h" href="{{ route('index') }}">
                        <img src="{{ asset('dashasset/img/SVG-NavBar-Logo.svg') }}" alt=""
                            class="img-fluid w-50" />
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                        <ul class="nav navbar-nav menu_nav ms-auto">
                            <li class="nav-item {{ Route::currentRouteName() == 'index' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('index') }}">Home</a>
                            </li>
                            <li class="nav-item {{ Route::currentRouteName() == 'about' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('about') }}">About</a>
                            </li>
                            <li class="nav-item {{ Route::currentRouteName() == 'shop' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('shop') }}">Shop</a>
                            </li>
                            <li class="nav-item {{ Route::currentRouteName() == 'contacts.index' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('contacts.index') }}">Contact</a>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="nav-item">
                                <a href="{{ route('cart') }}" class="cart">
                                    <span>
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('wishlist') }}">
                                    <span>
                                        <i class="fa-solid fa-heart"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                @auth
                                    <a href="{{ route('dashboard') }}">
                                        <span>
                                            Dashboard
                                        </span>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}">
                                        <span>
                                            <i class="fa-solid fa-user"></i>
                                        </span>
                                    </a>
                                @endauth
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
