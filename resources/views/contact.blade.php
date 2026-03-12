@extends('layouts.guest')

@section('content')
    <!-- Start Banner Area -->
    <section class="contact-banner-area banner-area d-flex align-items-center" id="hero">
        <div class="container">
            <div class="single-slide">
                <div class="banner-content text-center">
                    <h1 class="text-white">Contact Us</h1>
                    <p class="fs-5 text-white">
                        Call us when you need the best services
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!-- Contact Us section -->
    <section class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mt-3">
                    <div class="d-flex gap-3">
                        <div>
                            <img src="assets/img/call.png" width="40" alt="" class="img-fluid" />
                        </div>
                        <div>
                            <h3 class="fw-bold m-0">Call Us All Time</h3>
                            <p class="text-dark fs-6 m-0">+1 (832) 518-6347</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="d-flex gap-3">
                        <div>
                            <img src="assets/img/mail.png" width="50" alt="" class="img-fluid" />
                        </div>
                        <div>
                            <h3 class="fw-bold m-0">Send Us an Email</h3>
                            <p class="text-dark fs-6 m-0">megamerchantinc01@gmail.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="d-flex gap-3">
                        <div>
                            <img src="assets/img/location.png" width="50" alt="" class="img-fluid" />
                        </div>
                        <div>
                            <h3 class="fw-bold m-0">Our Address</h3>
                            <p class="text-dark fs-6 m-0">
                                811 Avenue D #170, Katy, TX 7749
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <h3 class="heading">Drop Us a Line</h3>
                <p>
                    We’re happy to answer any questions you have or provide you with an
                    estimate. <br class="d-none d-md-block"> Just send us a message in the form below with any
                    questions you may have.
                </p>
            </div>
            <div class="card border-0 shadow rounded-3 mt-3">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-7 mt-3 mt-md-0">
                            <div class="mb-3">
                                <x-alerts.error />
                            </div>
                            <x-forms.form action="{{ route('contacts.store') }}">
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <x-forms.label for="first_name" value="First Name" />
                                            <x-forms.field type="text" name="first_name" id="first_name"
                                                placeholder="John" value="{{ old('first_name') }}" />
                                            <x-forms.input-error :messages="$errors->get('first_name')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <x-forms.label for="last_name" value="Last Name" />
                                            <x-forms.field type="text" name="last_name" id="last_name" placeholder="Doe"
                                                value="{{ old('last_name') }}" />
                                            <x-forms.input-error :messages="$errors->get('last_name')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <x-forms.label for="email" value="Email" />
                                            <x-forms.field type="email" name="email" id="email"
                                                placeholder="example@mail.com" value="{{ old('email') }}" />
                                            <x-forms.input-error :messages="$errors->get('email')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <x-forms.label for="phone" value="Phone No." />
                                            <x-forms.field type="tel" name="phone" id="phone"
                                                placeholder="+123 456 789" value="{{ old('phone') }}" />
                                            <x-forms.input-error :messages="$errors->get('phone')" />
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <x-forms.label for="address" value="Address" />
                                        <x-forms.field type="text" name="address" id="address"
                                            placeholder="34 Madison Street, NY, USA 10005" value="{{ old('address') }}" />
                                        <x-forms.input-error :messages="$errors->get('address')" />
                                    </div>
                                    <div class="form-group mt-3">
                                        <x-forms.label for="note" value="Note" />
                                        <x-forms.textarea name="note" id="note" rows="5"
                                            placeholder="Type a message...">{{ old('note') }}</x-forms.textarea>
                                        <x-forms.input-error :messages="$errors->get('note')" />
                                    </div>
                                    <div class="col-md-12 mt-4 form-group">
                                        <button type="submit" value="submit" class="primary-btn bg-dark">
                                            Send Message
                                        </button>
                                    </div>
                                </div>
                            </x-forms.form>
                        </div>
                        <div class="col-md-5 mt-3 mt-md-0">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621"
                                frameborder="0" style="border: 0; width: 100%; height: 29rem" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End contact us section -->
@endsection
