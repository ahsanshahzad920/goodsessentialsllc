@extends('layouts.auth')

@section('pageTitle', 'Verify Email')

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
                    <div class="login_form_inner px-5 pt-5">

                        {{-- Password Reset Fields --}}
                        <div class="mb-3">
                            <h4 class="text-center mb-4">{{ __('Reset Password') }}</h4>
                            <x-forms.form :action="route('password.update')" class="row login_form" id="contactForm" novalidate="novalidate">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->token }}">

                                <div class="col-md-12 form-group">
                                    <x-forms.field type="email" name="email" id="email" placeholder="Email"
                                        value="{{ old('email', $request->email) }}" onfocus="this.placeholder=''"
                                        onblur="this.placeholder='Email'" readonly />
                                    <x-forms.input-error :messages="$errors->get('email')" />
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

                                <div class="col-md-12 form-group my-3">
                                    <button type="submit" value="submit" class="primary-btn bg-dark">
                                        Reset Password
                                    </button>
                                </div>
                            </x-forms.form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
