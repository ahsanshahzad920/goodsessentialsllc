@extends('layouts.auth')

@section('pageTitle', 'Register')

@section('content')
    <section class="login_box_area section_gap">
        <div class="container">
            <div class="row">
                <div class="col-md-6 pe-3 pe-md-0">
                    <div class="signup-bg card border-0 bg-transparent h-100 d-flex align-items-center rounded-0">
                        <div class="text-white hover">
                            <h4 class="text-white heading">Already have an account?</h4>
                            <p>
                                If you're one of us, log in to access your account and stay updated.
                                <br class="d-none d-md-block" />
                                We are glad to have you back!
                            </p>
                            <a class="btn create-account-btn rounded-0" href="{{ route('login') }}">Log In</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ps-3 ps-md-0">
                    <div class="login_form_inner pt-5">
                        <h3>Create account</h3>
                        <x-forms.form class="row login_form" action="{{ route('register') }}" id="contactForm"
                            novalidate="novalidate">
                            <div class="col-md-12 form-group">
                                <x-forms.field type="text" name="name" id="name" placeholder="Name"
                                    value="{{ old('name') }}" onfocus="this.placeholder=''"
                                    onblur="this.placeholder='Name'" />
                                <x-forms.input-error :messages="$errors->get('name')" />
                            </div>
                            <div class="col-md-12 form-group">
                                <x-forms.field type="email" name="email" id="email" placeholder="Email"
                                    value="{{ old('email') }}" onfocus="this.placeholder=''"
                                    onblur="this.placeholder='Email'" />
                                <x-forms.input-error :messages="$errors->get('email')" />
                            </div>
                            <div class="col-md-12 form-group">
                                <x-forms.field type="tel" name="phone" id="phone" placeholder="Phone Number"
                                    value="{{ old('phone') }}" onfocus="this.placeholder=''"
                                    onblur="this.placeholder='Phone Number'" />
                                <x-forms.input-error :messages="$errors->get('phone')" />
                            </div>
                            <div class="col-md-12 form-group">
                                <x-forms.field type="password" name="password" id="password" placeholder="Password"
                                    onfocus="this.placeholder=''" onblur="this.placeholder='Password'" />
                                <x-forms.input-error :messages="$errors->get('password')" />
                            </div>
                            <div class="col-md-12 form-group">
                                <x-forms.field type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Confirm Password" onfocus="this.placeholder=''"
                                    onblur="this.placeholder='Confirm Password'" />
                                <x-forms.input-error :messages="$errors->get('password_confirmation')" />
                            </div>
                            <button type="submit" class="btn text-white btn-dark rounded-0 mt-3">Create an account</button>
                        </x-forms.form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
