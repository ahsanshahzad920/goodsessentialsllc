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
                        <h3 class="text-center mb-4">
                            {{ __('Verify Your Email') }}
                        </h3>
                        <p class="text-center mb-4">
                            {{ __(' Before proceeding, please check your email for a verification link. If you did not receive the email, click the button below to request another.') }}
                        </p>

                        {{-- Resend Verification Email status --}}
                        @if (session('status'))
                            <div class="mb-4 text-muted text-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Resend Verification Link --}}
                        <div class="mb-3">
                            <x-forms.form :action="route('verification.send')">
                                <button type="submit" class="btn btn-success w-100">
                                    {{ __('Resend Verification Link') }}
                                </button>
                            </x-forms.form>
                        </div>

                        {{-- Logout Link --}}
                        <div class="text-center">
                            <x-forms.form :action="route('logout')">
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    {{ __('Logout') }}
                                </button>
                            </x-forms.form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
