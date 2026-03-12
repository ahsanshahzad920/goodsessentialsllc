@extends('layouts.guest')
@php

    use App\Models\Wishlist;

@endphp
@section('content')
    <!-- start banner Area -->
    <section class="banner-area d-flex align-items-center" id="hero">
        <div class="container">
            <div class="single-slide">
                <div class="banner-content text-center">
                    <h1>Welcome <br />Goodsessentialsllc</h1>
                    {{-- <p class="fs-1 text-dark"> --}}
                    {{-- Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi ab
                        <br class="d-none d-md-block" />
                        repellat, a iste voluptate porro at accusantium. Vero doloremque
                        <br class="d-none d-md-block" />
                        repudiandae ipsa, labore ratione quasi distinctio ipsum maxime
                        <br class="d-none d-md-block" />
                        blanditiis voluptates similique! --}}
                    <h1> 2024</h1>
                    {{-- </p> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- start features Area -->
    <section class="features-area section_gap">
        <div class="container">
            <div class="row features-inner">
                <!-- single features -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-features">
                        <div class="f-icon">
                            <img src="assets/img/features/f-icon1.png" alt="" />
                        </div>
                        <h6>Free Shipping</h6>
                        <p>Free Shipping on all order</p>
                    </div>
                </div>
                <!-- single features -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-features">
                        <div class="f-icon">
                            <img src="assets/img/features/f-icon2.png" alt="" />
                        </div>
                        <h6>Return Policy</h6>
                        <p>Free Shipping on all order</p>
                    </div>
                </div>
                <!-- single features -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-features">
                        <div class="f-icon">
                            <img src="assets/img/features/f-icon3.png" alt="" />
                        </div>
                        <h6>FREE SUPPORT</h6>
                        <p>Free Shipping on all order</p>
                    </div>
                </div>
                <!-- single features -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-features">
                        <div class="f-icon">
                            <img src="assets/img/features/f-icon4.png" alt="" />
                        </div>
                        <h6>Payment Secure</h6>
                        <p>Free Shipping on all order</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end features Area -->

    <!-- Start category Area -->
    <section class="category-area">
        <div class="container">
            <div class="text-center">
                <h1 class="fw-bold">Popular Categories</h1>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('shop', 'Baby Products') }}" class="text-decoration-none">
                                <div class="single-deal">
                                    <div class="overlay"></div>
                                    <img class="img-fluid" src="assets/img/category-3.jpg" alt=""
                                        style="width: 100%; height: 13rem; object-fit: cover" />
                                    <div class="deal-details">
                                        <h6 class="deal-title">Baby Products</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('shop', 'Beauty Products') }}" class="text-decoration-none">
                                <div class="single-deal">
                                    <div class="overlay"></div>
                                    <img class="img-fluid" src="assets/img/category-2.jpg" alt=""
                                        style="width: 100%; height: 13rem; object-fit: cover" />
                                    <div class="deal-details">
                                        <h6 class="deal-title">Beauty</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('shop', 'automotive') }}" class="text-decoration-none">
                                <div class="single-deal">
                                    <div class="overlay"></div>
                                    <img class="img-fluid" src="assets/img/category-4.jpg" alt=""
                                        style="width: 100%; height: 15rem; object-fit: cover" />
                                    <div class="deal-details">
                                        <h6 class="deal-title">Automotive</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('shop', 'Home and Kitchen') }}" class="text-decoration-none">
                        <div class="single-deal">
                            <div class="overlay"></div>
                            <img class="img-fluid w-100" src="assets/img/category-1.webp" alt=""
                                style="width: 100%; height: 30rem; object-fit: cover" />
                            <div class="deal-details">
                                <h6 class="deal-title">Kitchen</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- End category Area -->

    <!-- start product Area -->
    <section class="section_gap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Make your baby happy!</h1>
                        <p>
                            Welcome in our child’s world. All our products are made from
                            generations with passion.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse ($babyProducts as $babyProduct)
                    <div class="col-lg-3 col-md-4">
                        <a href="{{ route('productDetails', $babyProduct->id) }}" class="text-decoration-none">
                            <div class="card border-0 h-100 single-product">
                                <div class="card-header border-0 bg-transparent p-0">
                                    <img class="img-fluid"
                                        src="{{ $babyProduct->product_image ? asset('storage/' . $babyProduct->product_image) : 'https://via.placeholder.com/150' }}"
                                        alt="" />
                                </div>
                                <div class="card-body p-0">
                                    <div class="product-details">
                                        <h4 class="product-title">
                                            {{ $babyProduct->product_title ?? '' }}
                                        </h4>
                                        <div class="price">
                                            <h6>$
                                                {{ calculateDiscountPrice($babyProduct->price, $babyProduct->dis_price) ?? 0 }}
                                            </h6>
                                            <h6 class="l-through">$ {{ $babyProduct->price ?? 0 }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 product-details p-0">
                                    <div class="prd-bottom">
                                        @php
                                            $isInWishList = Wishlist::where('user_id', Auth::id())
                                                ->where('product_id', $babyProduct->id)
                                                ->exists();
                                        @endphp
                                        <a href="#" class="social-info wishlist-icon"
                                            data-product-id="{{ $babyProduct->id }}"
                                            data-in-wishlist="{{ $isInWishList ? 'true' : 'false' }}">
                                            <span class="{{ $isInWishList ? 'active' : '' }}">
                                                <i class="fa-solid fa-heart {{ $isInWishList ? 'active' : '' }}"></i>
                                            </span>
                                            <p class="hover-text">Wishlist</p>
                                        </a>
                                        <a href="{{ route('productDetails', $babyProduct->id) }}" class="social-info">
                                            <span><i class="fa-solid fa-eye"></i></span>
                                            <p class="hover-text">view more</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    No product found.
                @endforelse

            </div>
            <div class="text-center">
                <a href="{{ route('shop') }}" class="primary-btn bg-dark">Shop Now</a>
            </div>
        </div>
    </section>

    <!-- end product Area -->

    <!-- Start exclusive deal Area -->
    <section class="exclusive-deal-area">
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-6 no-padding exclusive-left">
                    <div class="row clock_sec clockdiv" id="clockdiv">
                        <div class="col-lg-12">
                            <h1 class="text-dark">Exclusive Hot Deal Ends Soon!</h1>
                            <p class="text-dark">
                                Who are in extremely love with eco friendly system.
                            </p>
                        </div>
                        <div class="col-lg-12">
                            <div class="row clock-wrap">
                                <div class="col clockinner1 clockinner">
                                    <h1 class="days">150</h1>
                                    <span class="smalltext">Days</span>
                                </div>
                                <div class="col clockinner clockinner1">
                                    <h1 class="hours">23</h1>
                                    <span class="smalltext">Hours</span>
                                </div>
                                <div class="col clockinner clockinner1">
                                    <h1 class="minutes">47</h1>
                                    <span class="smalltext">Mins</span>
                                </div>
                                <div class="col clockinner clockinner1">
                                    <h1 class="seconds">59</h1>
                                    <span class="smalltext">Secs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('shop') }}" class="primary-btn">Shop Now</a>
                </div>
                <div class="col-lg-6 no-padding exclusive-right">
                    <div class="active-exclusive-product-slider">
                        @forelse ($products as $product)
                            <!-- single exclusive carousel -->
                            <div class="single-exclusive-slider">
                                <img class="img-fluid"
                                    src="{{ $product->product_image ? asset('storage/' . $product->product_image) : 'https://via.placeholder.com/150' }}"
                                    alt="" />
                                <div class="product-details mt-3">
                                    <div class="price">
                                        <h6>$ {{ calculateDiscountPrice($product->price, $product->dis_price) ?? 0 }}</h6>
                                        <h6 class="l-through">$ {{ $product->price ?? 0 }}</h6>
                                    </div>
                                    <h4 class="product-title">
                                        {{ $product->product_title ?? '' }}
                                    </h4>

                                    {{-- <div class="d-flex align-items-center justify-content-center">
                                        @php
                                            $isInWishList = Wishlist::where('user_id', Auth::id())
                                                ->where('product_id', $otherProduct->id)
                                                ->exists();
                                        @endphp
                                        <a href="#" class="social-info wishlist-icon"
                                            data-product-id="{{ $otherProduct->id }}"
                                            data-in-wishlist="{{ $isInWishList ? 'true' : 'false' }}">
                                            <span class="{{ $isInWishList ? 'active' : '' }}">
                                                <i class="fa-solid fa-heart {{ $isInWishList ? 'active' : '' }}"></i>
                                            </span>
                                            <p class="hover-text">Wishlist</p>
                                        </a>
                                    </div> --}}

                                </div>
                            </div>
                        @empty
                            No product found.
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End exclusive deal Area -->

    <!-- Start related-product Area -->
    <section class="related-product-area section_gap_bottom mt-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Deals of the Week</h1>
                        <p>
                            Explore exclusive offers on quality products! From baby essentials to automotive upgrades, enjoy
                            unbeatable discounts on must-have items this week. Don’t miss out—shop now!
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        @forelse ($latestProducts as $latestProduct)
                            <div class="col-lg-4 col-md-4 col-sm-6 mb-20">
                                <div class="single-related-product d-flex">
                                    <a href="{{ route('productDetails', $latestProduct->id) }}">
                                        <img class="img-fluid"
                                            src="{{ $latestProduct->product_image ? asset('storage/' . $latestProduct->product_image) : 'https://via.placeholder.com/150' }}"
                                            alt="" style="width: 80px; height: 80px;" />
                                    </a>
                                    <div class="desc">
                                        <a href="{{ route('productDetails', $latestProduct->id) }}" class="title">
                                            {{ \Illuminate\Support\Str::words($latestProduct->product_title, 2, '...') ?? '' }}
                                        </a>

                                        <div class="price">
                                            <h6>$
                                                {{ calculateDiscountPrice($latestProduct->price, $latestProduct->dis_price) ?? 0 }}
                                            </h6>
                                            <h6 class="l-through">$ {{ $latestProduct->price ?? 0 }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            No record found.
                        @endforelse
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="ctg-right">
                        <a href="#">
                            <img class="img-fluid d-block mx-auto" src="assets/img/product-4.webp" alt="" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End related-product Area -->
@endsection

@push('style')
    <style>
        .product-title {
            font-size: 1.2rem;
            /* Default font size */
            white-space: nowrap;
            /* Prevent wrapping */
            overflow: hidden;
            /* Hide overflowed text */
            text-overflow: ellipsis;
            /* Add ellipsis for long titles */
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            .product-title {
                font-size: 1rem;
                /* Smaller font size on tablet */
            }
        }

        @media (max-width: 576px) {
            .product-title {
                font-size: 0.9rem;
                /* Smaller font size on mobile */
            }
        }
    </style>
    <style>
        .product-title {
            font-size: 1.2rem;
            /* Default font size */
            white-space: nowrap;
            /* Prevent wrapping */
            overflow: hidden;
            /* Hide overflowed text */
            text-overflow: ellipsis;
            /* Add ellipsis for long titles */
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            .product-title {
                font-size: 1rem;
                /* Smaller font size on tablet */
            }
        }

        @media (max-width: 576px) {
            .product-title {
                font-size: 0.9rem;
                /* Smaller font size on mobile */
            }
        }
    </style>
    <style>
        .social-info i.active {
            color: white;
        }

        .social-info span.active {
            background: red !important;
        }

        .owl-height {
            min-height: 580px !important;
            overflow: auto;
        }
    </style>
@endpush


@push('script')
    <script>
        // Pass user login status to JavaScript
        var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    </script>

    <script>
        document.querySelectorAll('.wishlist-icon').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // Check if the user is logged in
                if (!isLoggedIn) {
                    // Redirect to login page if the user is not logged in
                    window.location.href = '/login';
                    return;
                }

                let productId = this.getAttribute('data-product-id');
                let inWishlist = this.getAttribute('data-in-wishlist') === 'true';
                let icon = this.querySelector('i');

                fetch('/wishlist/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.success);
                        location.reload();

                        // Toggle the icon class based on the action
                        if (data.action === 'added') {
                            icon.classList.add(
                                'active'); // Change the icon to indicate it’s in the wishlist
                            this.setAttribute('data-in-wishlist', 'true');
                        } else {
                            icon.classList.remove(
                                'active'); // Change the icon to indicate it’s not in the wishlist
                            this.setAttribute('data-in-wishlist', 'false');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>
@endpush
