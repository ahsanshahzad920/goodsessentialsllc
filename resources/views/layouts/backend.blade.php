<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title> @yield('pageTitle', env('APP_NAME')) </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    {{-- Style Section --}}
    <x-backend.backend-layout-style />

    {{-- Custom Style --}}
    @stack('style')
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">

        {{-- Spinner --}}
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        {{-- Sidebar --}}
        <x-backend.sidebar />

        {{-- Content --}}
        <div class="content">

            {{-- Header --}}
            <x-backend.header />


            {{-- Content --}}
            @yield('content')
        </div>
    </div>


    {{-- Script Section --}}
    <x-backend.backend-layout-script />

    {{-- Custom Script --}}
    @stack('script')
</body>

</html>
