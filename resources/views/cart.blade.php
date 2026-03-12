@extends('layouts.guest')

@section('content')
    <!-- Start Banner Area -->
    <section class="addtocart-banner-area banner-area d-flex align-items-center" id="hero">
        <div class="container">
            <div class="single-slide">
                <div class="banner-content text-center">
                    <h1 class="text-white">Add to Cart</h1>
                    <p class="fs-5 text-white">
                        Conveniently store your favorite items for easy checkout!
                        <br class="d-none d-md-block" />
                        Simply click 'Add to Cart' to keep track of products you love
                        <br class="d-none d-md-block" />
                        and get ready to enjoy your shopping experience.
                    </p>

                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp

                            @if (session('cart') !== null)
                                @foreach (session('cart') as $id => $details)
                                    @php
                                        $productTotal = $details['quantity'] * $details['product_price'];
                                        $total += $productTotal;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="media d-flex gap-3">
                                                <div class="">
                                                    <img src="{{ asset('storage/' . $details['product_image'] ?? 'assets/img/default-image.png') }}"
                                                        width="70" alt="" />
                                                </div>
                                                <div class="media-body">
                                                    <p>
                                                    <div class="media-body word-wrap">
                                                        <p>{{ $details['product_title'] }}</p>
                                                    </div>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>${{ number_format($details['product_price'], 2) }}</h5>
                                        </td>
                                        <td>
                                            <div class="quantity d-flex justify-content-start align-items-center">
                                                <button class="btn qty-minus-btn btn-dark rounded-0" id="minusBtn"
                                                    data-id="{{ $details['product_id'] }}">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                                <input type="number" class="qty-input text-center rounded-0 form-control"
                                                    value="{{ $details['quantity'] }}" style="width: 50px"
                                                    data-id="{{ $id }}" />

                                                <button class="btn qty-plus-btn btn-dark rounded-0" id="plusBtn"
                                                    data-id="{{ $details['product_id'] }}">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="productTotalContainer">
                                            <h5 class="prodTotal">${{ number_format($productTotal, 2) }}</h5>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="border-0 mb-0">
                                    <td class="border-0"></td>
                                    <td class="border-0"></td>
                                    <td class="border-0">
                                        <h5>Subtotal</h5>
                                    </td>
                                    <td class="border-0">
                                        <h5 class="grandTotal">$ {{ number_format($total, 2) ?? 0 }}</h5>
                                    </td>
                                </tr>
                            @else
                                No item in the Cart.
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <div class="checkout_btn_inner d-flex align-items-center justify-content-end">
                        <a class="gray_btn rounded-0" href="{{ route('shop') }}">Continue Shopping</a>
                        <a class="btn btn-secondary px-4 rounded-0" href="{{ route('checkout') }}">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->
@endsection

@push('style')
    <style>
        td {
            padding: 15px 0 !important;
        }

        .word-wrap {
            word-wrap: break-word;
            /* Ensures long words break to the next line */
            overflow-wrap: break-word;
            /* Modern alternative for better browser support */
            white-space: normal;
            /* Makes sure text wraps normally */
            max-width: 400px;
            /* Optional: set a max width for better layout */
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

    {{-- increament --}}
    <script>
        $(document).on('click', '#plusBtn', function() {
            var productId = $(this).data('id');
            var button = $(this);

            $.ajax({
                url: '{{ route('increment.session') }}', // Define this route in your web.php
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}' // CSRF token for Laravel
                },
                success: function(response) {
                    button.closest('tr').find('.prodTotal').text('$' + parseFloat(response.total)
                        .toFixed(2));
                    // Update grand total
                    $('.grandTotal').text('$' + parseFloat(response.grandTotal).toFixed(2));

                    // Handle the response if needed
                    console.log(response.quantity);
                },
                error: function(xhr) {
                    // Handle error
                    console.log('Error:', xhr);
                }
            });
        });
    </script>

    {{-- decreament --}}
    <script>
        $(document).on('click', '#minusBtn', function() {
            var productId = $(this).data('id');
            var button = $(this);

            $.ajax({
                url: '{{ route('decrement.session') }}', // Define this route in your web.php
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    button.closest('tr').find('.prodTotal').text('$' + parseFloat(response.total)
                        .toFixed(2));
                    // Update grand total
                    $('.grandTotal').text('$' + parseFloat(response.grandTotal).toFixed(2));

                    // Handle the response if needed
                    console.log(response.message);
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        });
    </script>
@endpush
