@extends('layouts.auth')

@section('pageTitle', 'Forgot Password')


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
                        <h3 class="text-center mb-4">
                            {{ __('Forgot Your Password?') }}
                        </h3>
                        <p class="text-center mb-4">
                            {{ __('Enter your email address below and we will send you a link to reset your password.') }}
                        </p>

                        @if (session('status'))
                            <div class="mb-4 fw-bold text-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <x-forms.form :action="route('password.email')" class="row login_form" id="contactForm" novalidate="novalidate">
                            <div class="col-md-12 form-group">
                                <x-forms.field type="email" name="email" id="email" placeholder="Email"
                                    onfocus="this.placeholder=''" onblur="this.placeholder='Email'" :value="old('email')" />
                                <x-forms.input-error :messages="$errors->get('email')" />
                            </div>

                            {{-- Submit Button --}}
                            <div class="col-md-12 form-group my-3">
                                <button type="submit" value="submit" class="primary-btn bg-dark">
                                    Send Password Reset Link
                                </button>
                            </div>

                            {{-- Back to Login --}}
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="text-muted">
                                    {{ __('Back to Login') }}
                                </a>
                            </div>
                        </x-forms.form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
