@extends('layouts.guest')

@section('content')
<div class="container-shop space-y-12 py-8 sm:space-y-16">

    {{-- Hero section --}}
    <section class="relative isolate overflow-hidden rounded-3xl bg-pink-50 px-6 py-12 sm:px-10 sm:py-16 lg:px-16 lg:py-20">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-pink-600 shadow-sm">
                <i class="fa-solid fa-heart"></i>
                Welcome to Flareoncorp
            </span>

            <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                Little Things,
                <span class="text-pink-500">Big Smiles.</span>
            </h1>

            <p class="mt-5 max-w-xl text-base leading-8 text-gray-600 sm:text-lg">
                Flareoncorp is your one-stop destination for quality products across baby essentials,
                automotive accessories, beauty, and home and kitchen.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('shop') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-full bg-pink-500 px-7 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-pink-600">
                    Explore Our Shop
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <a href="#our-story"
                   class="inline-flex items-center justify-center rounded-full border border-pink-200 bg-white px-7 py-3.5 text-sm font-bold text-gray-700 transition hover:border-pink-400 hover:text-pink-600">
                    Our Story
                </a>
            </div>
        </div>

        {{-- Decorative shapes --}}
        <div class="pointer-events-none absolute -right-12 -top-12 -z-0 h-56 w-56 rounded-full bg-pink-100 sm:h-80 sm:w-80"></div>
        <div class="pointer-events-none absolute -bottom-16 right-20 -z-0 h-40 w-40 rounded-full bg-purple-100 sm:h-52 sm:w-52"></div>

        <div class="pointer-events-none absolute right-10 top-10 hidden rotate-12 text-5xl text-pink-300 sm:block">
            <i class="fa-solid fa-heart"></i>
        </div>
        <div class="pointer-events-none absolute bottom-10 right-20 hidden -rotate-12 text-4xl text-purple-300 sm:block">
            <i class="fa-solid fa-star"></i>
        </div>
    </section>


    {{-- Our story --}}
    <section id="our-story" class="grid grid-cols-1 items-center gap-8 md:grid-cols-2 lg:gap-14">

        <div class="relative">
            <div class="overflow-hidden rounded-3xl bg-pink-50 p-3 sm:p-5">
                <img
                    src="{{ asset('assets/img/about.jpg') }}"
                    alt="Discover the Flareoncorp shopping experience"
                    class="h-72 w-full rounded-2xl object-cover sm:h-96"
                    loading="lazy"
                >
            </div>

            <div class="relative mx-4 -mt-8 rounded-2xl border border-gray-100 bg-white p-5 shadow-lg sm:mx-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-pink-50 text-pink-500">
                        <i class="fa-solid fa-heart text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Shopping Made Special</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Products for your everyday needs.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <span class="inline-flex rounded-full bg-purple-50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-purple-600">
                Get to Know Us
            </span>

            <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-4xl">
                Combining Years of
                <span class="text-pink-500">Hard Work</span>
            </h2>

            <p class="text-sm leading-7 text-gray-600 sm:text-base">
                At Flareoncorp, we've combined years of dedication and passion to bring you a diverse range of products that cater to your everyday needs. Our journey is built on hard work and a commitment to quality, ensuring that every item we offer meets our standards.
            </p>

            <p class="text-sm leading-7 text-gray-600 sm:text-base">
                From baby essentials to automotive accessories, beauty products, and home goods, we strive to provide reliable solutions for your life.
            </p>

            <p class="text-sm leading-7 text-gray-600 sm:text-base">
                With a focus on customer satisfaction, our team continuously works to source and deliver products across various categories. Whether you're shopping for your home, family, or personal use, Flareoncorp is here to make your experience smooth and enjoyable.
            </p>

            <a href="{{ route('shop') }}"
               class="inline-flex items-center gap-2 font-bold text-pink-600 transition hover:gap-3">
                Discover Our Products
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>


    {{-- Popular categories --}}
    <section aria-label="Popular categories" class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="text-sm font-bold uppercase tracking-wider text-pink-500">
                    Find Your Favorites
                </span>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900">
                    Shop by Category
                </h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Explore something special for every part of your life.
                </p>
            </div>

            <a href="{{ route('shop') }}"
               class="inline-flex items-center gap-2 text-sm font-bold text-pink-600 hover:text-pink-700">
                View All Products
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Baby products --}}
            <a href="{{ route('shop', 'Baby Products') }}"
               class="group relative isolate min-h-64 overflow-hidden rounded-3xl bg-pink-50">
                <img
                    src="{{ asset('assets/img/category-3.jpg') }}"
                    alt="Baby products"
                    class="absolute inset-0 -z-20 h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    loading="lazy"
                >
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>

                <div class="absolute inset-x-0 bottom-0 p-5">
                    <span class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-pink-600">
                        <i class="fa-solid fa-baby"></i>
                    </span>
                    <h3 class="text-xl font-bold text-white">Baby Products</h3>
                    <p class="mt-1 text-sm text-white/85">Little essentials, lots of love.</p>
                    <span class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-white">
                        Explore Category
                        <i class="fa-solid fa-arrow-right transition group-hover:translate-x-1"></i>
                    </span>
                </div>
            </a>

            {{-- Beauty --}}
            <a href="{{ route('shop', 'Beauty Products') }}"
               class="group relative isolate min-h-64 overflow-hidden rounded-3xl bg-purple-50">
                <img
                    src="{{ asset('assets/img/category-2.jpg') }}"
                    alt="Beauty products"
                    class="absolute inset-0 -z-20 h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    loading="lazy"
                >
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>

                <div class="absolute inset-x-0 bottom-0 p-5">
                    <span class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-purple-600">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </span>
                    <h3 class="text-xl font-bold text-white">Beauty Products</h3>
                    <p class="mt-1 text-sm text-white/85">Everyday self-care favorites.</p>
                    <span class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-white">
                        Explore Category
                        <i class="fa-solid fa-arrow-right transition group-hover:translate-x-1"></i>
                    </span>
                </div>
            </a>

            {{-- Automotive --}}
            <a href="{{ route('shop', 'Automotive') }}"
               class="group relative isolate min-h-64 overflow-hidden rounded-3xl bg-blue-50">
                <img
                    src="{{ asset('assets/img/category-4.jpg') }}"
                    alt="Automotive accessories"
                    class="absolute inset-0 -z-20 h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    loading="lazy"
                >
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>

                <div class="absolute inset-x-0 bottom-0 p-5">
                    <span class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-blue-600">
                        <i class="fa-solid fa-car"></i>
                    </span>
                    <h3 class="text-xl font-bold text-white">Automotive</h3>
                    <p class="mt-1 text-sm text-white/85">Accessories for your journey.</p>
                    <span class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-white">
                        Explore Category
                        <i class="fa-solid fa-arrow-right transition group-hover:translate-x-1"></i>
                    </span>
                </div>
            </a>

            {{-- Home and kitchen --}}
            <a href="{{ route('shop', 'Home and Kitchen') }}"
               class="group relative isolate min-h-64 overflow-hidden rounded-3xl bg-amber-50">
                <img
                    src="{{ asset('assets/img/category-1.webp') }}"
                    alt="Home and kitchen products"
                    class="absolute inset-0 -z-20 h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    loading="lazy"
                >
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>

                <div class="absolute inset-x-0 bottom-0 p-5">
                    <span class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-amber-600">
                        <i class="fa-solid fa-house"></i>
                    </span>
                    <h3 class="text-xl font-bold text-white">Home & Kitchen</h3>
                    <p class="mt-1 text-sm text-white/85">Make your space feel like home.</p>
                    <span class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-white">
                        Explore Category
                        <i class="fa-solid fa-arrow-right transition group-hover:translate-x-1"></i>
                    </span>
                </div>
            </a>

        </div>
    </section>


    {{-- Why shop with us --}}
    <section class="rounded-3xl bg-purple-50 px-5 py-10 sm:px-10 sm:py-12">
        <div class="mx-auto max-w-2xl text-center">
            <span class="text-sm font-bold uppercase tracking-wider text-purple-600">
                The Flareoncorp Experience
            </span>
            <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900">
                Why Shop With Us?
            </h2>
            <p class="mt-3 text-sm leading-6 text-gray-600">
                We want every visit to our store to be simple, enjoyable, and convenient.
            </p>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-pink-50 text-pink-500">
                    <i class="fa-solid fa-box-open text-2xl"></i>
                </div>
                <h3 class="mt-4 font-bold text-gray-900">A Variety of Products</h3>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Explore products across categories for your home, family, and everyday needs.
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-purple-500">
                    <i class="fa-solid fa-star text-2xl"></i>
                </div>
                <h3 class="mt-4 font-bold text-gray-900">Quality Focused</h3>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    We aim to offer products that meet our commitment to quality.
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 text-center sm:col-span-2 lg:col-span-1">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-500">
                    <i class="fa-solid fa-face-smile text-2xl"></i>
                </div>
                <h3 class="mt-4 font-bold text-gray-900">Customer Satisfaction</h3>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    We strive to make your shopping experience smooth and enjoyable.
                </p>
            </div>
        </div>
    </section>


    {{-- Testimonials --}}
    <section aria-label="Customer testimonials" class="space-y-6">
        <div class="text-center">
            <span class="text-sm font-bold uppercase tracking-wider text-pink-500">
                Kind Words from Customers
            </span>
            <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900">
                What Our Customers Say
            </h2>
            <p class="mt-3 text-sm text-gray-500">
                Feedback shared by customers about their shopping experiences.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            <article class="flex flex-col rounded-3xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-1 text-amber-400" aria-label="5 out of 5 stars">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fa-solid fa-star text-sm"></i>
                    @endfor
                </div>

                <i class="fa-solid fa-quote-left mt-5 text-2xl text-pink-200"></i>

                <p class="mt-3 flex-1 text-sm leading-7 text-gray-600">
                    I received my suit today, and I'm so happy with the fabric quality. The stitching is excellent and professional, and the delivery was fast. I'll be buying my suits for future events from Flareoncorp.
                </p>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-pink-100 font-bold text-pink-600">
                        NC
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Naurine Chaurania</h3>
                        <p class="mt-1 text-xs text-gray-500">USA</p>
                    </div>
                </div>
            </article>

            <article class="flex flex-col rounded-3xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-1 text-amber-400" aria-label="5 out of 5 stars">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fa-solid fa-star text-sm"></i>
                    @endfor
                </div>

                <i class="fa-solid fa-quote-left mt-5 text-2xl text-purple-200"></i>

                <p class="mt-3 flex-1 text-sm leading-7 text-gray-600">
                    I had a wonderful experience with Flareoncorp. Their customer service was awesome and patient while I added and removed items from my order. They made sure everything was perfect.
                </p>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-purple-100 font-bold text-purple-600">
                        RS
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Richard Susan</h3>
                        <p class="mt-1 text-xs text-gray-500">USA</p>
                    </div>
                </div>
            </article>

            <article class="flex flex-col rounded-3xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-1 text-amber-400" aria-label="5 out of 5 stars">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fa-solid fa-star text-sm"></i>
                    @endfor
                </div>

                <i class="fa-solid fa-quote-left mt-5 text-2xl text-amber-200"></i>

                <p class="mt-3 flex-1 text-sm leading-7 text-gray-600">
                    I had a delightful shopping experience. The products were affordable, and I was satisfied with their originality. The staff were friendly and comforting.
                </p>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-100 font-bold text-amber-700">
                        RP
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Robert Patricia</h3>
                        <p class="mt-1 text-xs text-gray-500">USA</p>
                    </div>
                </div>
            </article>

        </div>
    </section>


    {{-- Call to action --}}
    <section class="rounded-3xl bg-pink-500 px-6 py-10 text-center sm:px-10 sm:py-12">
        <div class="mx-auto max-w-2xl">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white/20 text-white">
                <i class="fa-solid fa-bag-shopping text-2xl"></i>
            </div>

            <h2 class="mt-4 text-3xl font-extrabold text-white">
                Ready to Find Your Favorites?
            </h2>

            <p class="mt-3 text-sm leading-7 text-white/90 sm:text-base">
                Explore our collections and discover products for your everyday needs.
            </p>

            <a href="{{ route('shop') }}"
               class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-pink-600 transition hover:bg-pink-50">
                Start Shopping
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>


    {{-- Trust bar --}}
    <x-shop.trust-bar />

</div>
@endsection