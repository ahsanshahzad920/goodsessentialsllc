@extends('layouts.guest')
@php
    use App\Models\Wishlist;
    use App\Models\Product;
@endphp
@section('content')
    <!-- Start Banner Area -->
    <section class="wishlist-banner-area banner-area d-flex align-items-center" id="hero">
        <div class="container">
            <div class="single-slide">
                <div class="banner-content text-center">
                    <h1 class="text-white">Add to Wishlist</h1>
                    <p class="fs-5 text-white">
                        Keep track of your favorite products with our wishlist feature!
                        <br class="d-none d-md-block" />
                        Save items you love, from baby essentials to beauty must-haves,
                        <br class="d-none d-md-block" />
                        and never miss out on great deals!
                    </p>

                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Wishlist Area =================-->
    <!--================Wishlist Area =================-->
    @php
        $productIDs = Wishlist::where('user_id', auth()->id())->pluck('product_id');
        $wishlistProducts = Product::whereIn('id', $productIDs)->get();
    @endphp
    <div class="container mt-5">
        <div class="row">
            @if ($wishlistProducts->isEmpty())
                <div class="col-12 text-center">
                    <h3 class="text-white">Your wishlist is empty!</h3>
                </div>
            @else
                <div class="container mt-5">
                    <div class="row d-flex align-items-stretch"> <!-- Add d-flex align-items-stretch -->
                        @if ($wishlistProducts->isEmpty())
                            <div class="col-12 text-center">
                                <h3 class="text-white">Your wishlist is empty!</h3>
                            </div>
                        @else
                            @foreach ($wishlistProducts as $product)
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                    <div class="card h-100"> <!-- Use h-100 to make the card take the full height -->
                                        <img src="{{ asset('storage/' . $product->product_image) }}" class="card-img-top"
                                            alt="{{ $product->title }}">
                                        <div class="card-body d-flex flex-column"> <!-- Use flex-column -->
                                            <h5 class="card-title">{{ $product->product_title ?? '' }}</h5>
                                            <p class="card-text">{!! $product->product_description ?? '' !!}</p>
                                            <div class="mt-auto"> <!-- Push the buttons to the bottom -->
                                                <a href="{{ route('productDetails', $product->id) }}"
                                                    class="btn btn-primary">View Product</a>
                                                <form action="{{ route('wishlist.remove') }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <button type="submit"
                                                        class="btn btn-danger remove-from-wishlist">Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            @endif
        </div>
    </div>
    <!--================End Wishlist Area =================-->
@endsection

@push('style')
    <style>
        td {
            padding: 15px 0 !important;
        }
    </style>
    <style>
        .card {
            min-height: 300px;
            /* Set a minimum height for the card */
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // Event delegation for plus button
            $(document).on("click", ".qty-plus-btn", function() {
                var input = $(this).siblings(".qty-input");
                input.val(parseInt(input.val()) + 1);
            });

            // Event delegation for minus button
            $(document).on("click", ".qty-minus-btn", function() {
                var input = $(this).siblings(".qty-input");
                var currentValue = parseInt(input.val());
                if (currentValue > 1) {
                    input.val(currentValue - 1);
                }
            });
        });
    </script>
@endpush
