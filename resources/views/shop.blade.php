@extends('layouts.guest')
@php

    use App\Models\Wishlist;

@endphp
@section('content')
    <!-- Start Banner Area -->
    <section class="shop-banner-area banner-area d-flex align-items-center" id="hero">
        <div class="container">
            <div class="single-slide">
                <div class="banner-content text-center">
                    <h1 class="text-white">
                        {{ request()->category_name ?? 'Products Catalog' }}
                    </h1>
                    {{-- <p class="fs-5 text-white">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi ab
                        <br class="d-none d-md-block" />
                        repellat, a iste voluptate porro at accusantium. Vero doloremque
                        <br class="d-none d-md-block" />
                        repudiandae ipsa, labore ratione quasi distinctio ipsum maxime
                        <br class="d-none d-md-block" />
                        blanditiis voluptates similique!
                    </p> --}}
                </div>
            </div>
        </div>
    </section>

    <!-- End Banner Area -->
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-4">
                <div class="sidebar-categories">
                    <div class="head">Categories</div>
                    <ul class="main-categories shadow-none">
                        @forelse ($categories as $category)
                            <li class="main-nav-list">
                                <a href="{{ route('shop', $category->name) }}">
                                    {{ $category->name ?? '' }}
                                    <span class="number">
                                        ({{ $category->products_count ?? 0 }})
                                    </span>
                                </a>
                            </li>
                        @empty
                            No Category Found.
                        @endforelse

                    </ul>
                </div>
                <div class="sidebar-filter mt-50">
                    <!-- <div class="top-filter-head">Product Filters</div> -->
                    <div class="common-filter">
                        <div class="head">Color</div>
                        <form action="#">
                            <ul>
                                <li class="filter-list">
                                    <input class="pixel-radio" type="radio" id="black" name="color" /><label
                                        for="black">Black<span>(29)</span></label>
                                </li>
                                <li class="filter-list">
                                    <input class="pixel-radio" type="radio" id="balckleather" name="color" /><label
                                        for="balckleather">Black Leather<span>(29)</span></label>
                                </li>
                                <li class="filter-list">
                                    <input class="pixel-radio" type="radio" id="blackred" name="color" /><label
                                        for="blackred">Black with
                                        red<span>(19)</span></label>
                                </li>
                                <li class="filter-list">
                                    <input class="pixel-radio" type="radio" id="gold" name="color" /><label
                                        for="gold">Gold<span>(19)</span></label>
                                </li>
                                <li class="filter-list">
                                    <input class="pixel-radio" type="radio" id="spacegrey" name="color" /><label
                                        for="spacegrey">Spacegrey<span>(19)</span></label>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-8">
                <div class="form-group">
                    <form method="GET" action="{{ route('shop') }}">
                        <input type="search" name="search" class="form-control py-2" placeholder="Search your product"
                            value="{{ request('search') }}" autocomplete="off" />
                    </form>
                </div>

                <!-- Start Best Seller -->
                <section class="lattest-product-area pb-40 category-list">
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="col-lg-4 col-md-6">
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
                                                <h6>$
                                                    {{ calculateDiscountPrice($product->price, $product->dis_price) ?? 0 }}
                                                </h6>
                                                <h6 class="l-through">$ {{ $product->price ?? 0 }}</h6>
                                            </div>
                                            <div class="prd-bottom">
                                                {{-- <a href="#" class="social-info">
                                                    <span><i class="fa-solid fa-cart-shopping"></i></span>
                                                    <p class="hover-text">add to bag</p>
                                                </a> --}}
                                                {{-- <a href="#" class="social-info">
                                                    <span><i class="fa-solid fa-heart"></i></span>
                                                    <p class="hover-text">Wishlist</p>
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
                                                        <i
                                                            class="fa-solid fa-heart {{ $isInWishList ? 'active' : '' }}"></i>
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
                </section>
                <!-- End Best Seller -->
                <!-- Start Filter Bar -->
                <div class="mb-5">
                    {{-- <div class="form-group">
                        <select class="form-control form-select" aria-label="Default select example"
                            id="exampleFormControlSelect1">
                            <option>Show 5</option>
                            <option>Show 10</option>
                            <option>Show 15</option>
                        </select>
                    </div> --}}
                    {{-- <div> --}}
                    {{ $products->links() }}
                    {{-- </div> --}}
                    {{-- <div class="pagination">
                        <a href="#" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
                        <a href="#" class="active">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <a href="#" class="dot-dot"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                        <a href="#">6</a>
                        <a href="#" class="next-arrow"><i class="fa fa-long-arrow-right"
                                aria-hidden="true"></i></a>
                    </div> --}}
                </div>
                <!-- End Filter Bar -->
            </div>
        </div>
    </div>
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
