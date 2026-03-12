<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png" />
    <!-- Author Meta -->
    <meta name="author" content="CodePixar" />
    <!-- Meta Description -->
    <meta name="description" content="" />
    <!-- Meta Keyword -->
    <meta name="keywords" content="" />
    <!-- meta character set -->
    <meta charset="UTF-8" />

    <title> @yield('pageTitle', env('APP_NAME')) </title>

    {{-- Style Section --}}
    <x-frontend.guest-layout-style />

    {{-- Custom Style --}}
    @stack('style')

    <style>
        .nav-item.active .nav-link{
            font-weight: bold !important;
        }
    </style>

</head>

<body>

    {{-- Header --}}
    <x-frontend.header />


    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    <x-frontend.footer />

    {{-- Script Section --}}
    <x-frontend.guest-layout-script />

    {{-- Custom Script --}}
    @stack('script')

</body>

</html>
