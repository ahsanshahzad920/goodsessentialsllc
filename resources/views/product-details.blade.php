@extends('layouts.guest')
@php

    use App\Models\Wishlist;

@endphp
@section('content')
    <!-- Start Banner Area -->
    <section class="detail-banner-area banner-area d-flex align-items-center" id="hero">
        <div class="container">
            <div class="single-slide">
                <div class="banner-content text-center">
                    <h1 class="text-white">Product Detail</h1>
                    <p class="fs-5 text-white">
                        Discover everything you need to know about our product!
                        <br class="d-none d-md-block" />
                        From features and benefits to specifications and care instructions,
                        <br class="d-none d-md-block" />
                        we provide all the details to help you make an informed choice.
                    </p>

                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Single Product Area =================-->
    <div class="product_image_area mb-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    {{-- <div id="carouselExample" class="carousel slide">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/img/product-1.webp" alt="" class="img-fluid w-100" />
                            </div>
                            <div class="carousel-item">
                                <img src="assets/img/product-2.webp" alt="" class="img-fluid w-100" />
                            </div>
                            <div class="carousel-item">
                                <img src="assets/img/product-3.webp" alt="" class="img-fluid w-100" />
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div> --}}
                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="" class="img-fluid w-100" />

                </div>
                <div class="col-md-7">
                    <div class="s_product_text m-0">
                        <h3> {{ $product->product_title ?? '' }} </h3>
                        <h2 class="">$ {{ calculateDiscountPrice($product->price, $product->dis_price) ?? 0 }}</h2>
                        <p class="mb-3"><span class="me-4">Category</span> : {{ $product->category->name ?? '' }}</p>
                        <div class="m-0">
                            {!! $product->product_description ?? '' !!}
                        </div>
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="quantity d-flex justify-content-start align-items-center my-3">
                                <button class="btn qty-minus-btn btn-dark rounded-0" type="button" id="minusBtn">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="text" name="quantity" id="quantityInput"
                                    class="qty-input text-center rounded-0 form-control" value="1"
                                    style="width: 50px" />
                                <button class="btn qty-plus-btn btn-dark rounded-0" type="button" id="plusBtn">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            @php
                                $isInCart = session()->has('cart') && array_key_exists($product->id, session('cart'));
                            @endphp


                            @if ($isInCart)
                                <div class="card_area d-flex align-items-center">
                                    <button type="button" disabled class="primary-btn bg-dark text-white">Already
                                        In</button>
                                </div>
                            @else
                                <div class="card_area d-flex align-items-center">
                                    <button type="submit" class="primary-btn bg-dark text-white">Add to Cart</button>
                                </div>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--================End Single Product Area =================-->

    <!-- Interested section -->
    <section class="my-5">
        <div class="container">
            <div class="text-center">
                <h3 class="heading fs-2">Trending category</h3>
            </div>
            <div class="row mt-4">
                @forelse ($products as $product)
                    <div class="col-md-3">
                        <a href="{{ route('productDetails', $product->id) }}" class="text-decoration-none">
                            <div class="single-product">
                                <div class="image-container">
                                    <img class="img-fluid" src="{{ asset('storage/' . $product->product_image) }}"
                                        alt="" />
                                </div>
                                <div class="product-details">
                                    <h6 class="product-title">
                                        {{ $product->product_title ?? '' }}
                                    </h6>
                                    <div class="price">
                                        <h6>$ {{ calculateDiscountPrice($product->price, $product->dis_price) ?? 0 }} </h6>
                                        <h6 class="l-through">$ {{ $product->price ?? 0 }}</h6>
                                    </div>
                                    <div class="prd-bottom">
                                        {{-- <a href="#" class="social-info">
                                            <span><i class="fa-solid fa-cart-shopping"></i></span>
                                            <p class="hover-text">add to bag</p>
                                        </a> --}}
                                        @php
                                            $isInWishList = Wishlist::where('user_id', Auth::id())
                                                ->where('product_id', $product->id)
                                                ->exists();
                                        @endphp
                                        <a href="#" class="social-info wishlist-icon"
                                            data-product-id="{{ $product->id }}"
                                            data-in-wishlist="{{ $isInWishList ? 'true' : 'false' }}">
                                            <span class="{{ $isInWishList ? 'active' : '' }}">
                                                <i class="fa-solid fa-heart {{ $isInWishList ? 'active' : '' }}"></i>
                                            </span>
                                            <p class="hover-text">Wishlist</p>
                                        </a>
                                        <a href="{{ route('productDetails', $product->id) }}" class="social-info">
                                            <span><i class="fa-solid fa-eye"></i></span>
                                            <p class="hover-text">view more</p>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                @empty
                    No Product Found.
                @endforelse
            </div>
        </div>
    </section>
    <!-- End interested section -->
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
    </style>
@endpush

@push('script')
    <script>
        document.getElementById('plusBtn').addEventListener('click', function() {
            let qtyInput = document.getElementById('quantityInput');
            let currentValue = parseInt(qtyInput.value);
            qtyInput.value = currentValue + 1;
        });

        document.getElementById('minusBtn').addEventListener('click', function() {
            let qtyInput = document.getElementById('quantityInput');
            let currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        });
    </script>
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
