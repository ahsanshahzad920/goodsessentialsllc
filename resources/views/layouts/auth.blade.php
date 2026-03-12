<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png" />
    <!-- Author Meta -->
    <meta name="author" content="CodePixar" />
    <!-- Meta  -->
    <meta name="" content="" />
    <!-- Meta Keyword -->
    <meta name="keywords" content="" />
    <!-- meta character set -->
    <meta charset="UTF-8" />
    <!-- Site Title -->
    <title> @yield('pageTitle', env('APP_NAME')) </title>

    {{-- Style Section --}}
    <x-frontend.guest-layout-style />

    {{-- Custom Style --}}
    @stack('style')

</head>

<body>

    @yield('content')

    {{-- Script Section --}}
    <x-frontend.guest-layout-script />

    {{-- Custom Script --}}
    @stack('script')
</body>

</html>
