@extends('layouts.guest')

@section('content')
    <!-- Start Banner Area -->
    <section class="checkout-banner-area banner-area d-flex align-items-center" id="hero">
        <div class="container">
            <div class="single-slide">
                <div class="banner-content text-center">
                    <h1 class="text-white">Checkout</h1>
                    <p class="fs-5 text-white">
                        Ready to complete your purchase?
                        <br class="d-none d-md-block" />
                        Review your items, enter your details, and enjoy a seamless shopping experience.
                        <br class="d-none d-md-block" />
                        We're here to ensure a secure and quick checkout process!
                    </p>

                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Checkout Area =================-->
    <section class="checkout_area section_gap">
        <div class="container">
            <div class="billing_details">
                <div class="row">
                    <div class="col-md-8">
                        <h3>Billing Details</h3>
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="mb-1 fs-6">First Name</label>
                                    <input type="text" placeholder="John Doe" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="mb-1 fs-6">Last Name</label>
                                    <input type="text" placeholder="Williams" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="mb-1 fs-6">Phone Number</label>
                                    <input type="tel" placeholder="+123 456 789" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="mb-1 fs-6">Email</label>
                                    <input type="email" placeholder="example@mail.com" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="mb-1 fs-6">Address</label>
                                <input type="text" placeholder="Address no 1" class="form-control" />
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="mb-1 fs-6">City</label>
                                    <input type="text" placeholder="City" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="mb-1 fs-6">Zip Code</label>
                                    <input type="text" placeholder="44454" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="mb-1 fs-6">Additional Notes</label>
                                <textarea rows="5" placeholder="Additional Notes" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="order_box">
                            <h2>Your Order</h2>
                            <ul class="list">
                                <li>
                                    <a href="#" class="fw-bold text-dark">Product <span>Total</span></a>
                                </li>

                                @if (session('cart') !== null)
                                    @foreach (session('cart') as $id => $details)
                                        @php
                                            $productTotal = $details['quantity'] * $details['product_price'];
                                        @endphp
                                        <li>
                                            <a href="#" class="text-dark">{{ $details['product_title'] }}
                                                <span class="middle">x {{ $details['quantity'] }}</span>
                                                <span class="last">${{ number_format($productTotal, 2) }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li>No items in your cart.</li>
                                @endif
                            </ul>

                            <ul class="list list_2">
                                @if (session('cart') !== null)
                                    @php
                                        $subtotal = 0;
                                        foreach (session('cart') as $details) {
                                            $subtotal += $details['quantity'] * $details['product_price'];
                                        }
                                    @endphp
                                    <li>
                                        <a href="#">Subtotal <span>${{ number_format($subtotal, 2) }}</span></a>
                                    </li>
                                    <li>
                                        <a href="#">Shipping <span>Flat rate: $50.00</span></a>
                                    </li>
                                    <li>
                                        <a href="#">Total <span>${{ number_format($subtotal + 50, 2) }}</span></a>
                                        <!-- Total includes flat shipping rate -->
                                    </li>
                                @endif
                            </ul>

                            <div class="creat_account mt-2">
                                <input type="checkbox" id="f-option4" name="selector" />
                                <label for="f-option4">I’ve read and accept the </label>
                                <a href="#">terms & conditions*</a>
                            </div>
                            <a class="primary-btn" href="#">Proceed to Paypal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
