@extends('layouts.auth')

@section('pageTitle', 'Login')

@section('content')
    <!--================Login Box Area =================-->
    <section class="login_box_area section_gap">
        <div class="container">
            <div class="row">
                <div class="col-md-6 pe-3 pe-md-0">
                    <div class="login-bg card border-0 bg-transparent h-100 d-flex align-items-center rounded-0">
                        <div class="text-white hover">
                            <h4 class="text-white heading">New to our website?</h4>
                            <p>
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                                <br class="d-none d-md-block" />
                                Odit molestiae possimus digniss
                            </p>
                            <a class="btn create-account-btn rounded-0" href="{{ route('register') }}">Create an Account</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ps-3 ps-md-0">
                    <div class="login_form_inner">
                        <h3>Log in to enter</h3>

                        {{-- Reset Password status --}}
                        @if (session('status'))
                            <div class="mb-4 fw-bold text-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <x-forms.form class="row login_form" action="{{ route('login') }}" id="contactForm"
                            novalidate="novalidate">
                            <div class="col-md-12 form-group">
                                <x-forms.field type="email" name="email" id="email" placeholder="Email"
                                    onfocus="this.placeholder=''" onblur="this.placeholder='Email'" :value="old('email')" />
                                <x-forms.input-error :messages="$errors->get('email')" />
                            </div>
                            <div class="col-md-12 form-group">
                                <x-forms.field type="password" name="password" id="password" placeholder="Password"
                                    onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" />
                                <x-forms.input-error :messages="$errors->get('password')" />
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="creat_account">
                                    <input type="checkbox" name="remember" id="f-option2" name="selector" />
                                    <label for="f-option2">Keep me logged in</label>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" value="submit" class="primary-btn bg-dark">
                                    Log In
                                </button>
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            </div>
                        </x-forms.form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Login Box Area =================-->
@endsection
