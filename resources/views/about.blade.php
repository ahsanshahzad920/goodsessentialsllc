@extends('layouts.guest')

@php
    \App\Services\SeoService::set(
        'About Us | ' . config('app.name'),
        'Learn about Goods Essentials, a Pakistan-based baby and mom essentials store offering wholesale-rate pricing on quality products, with Cash on Delivery and fast delivery.',
    );

    $testimonials = \App\Models\Review::approved()->featured()->latest()->take(6)->get();
    $aboutTiles = \App\Models\Category::homeTiles();
@endphp

@section('content')
<div class="container-shop space-y-12 py-8 sm:space-y-16">

    {{-- Hero section --}}
    <section class="relative isolate overflow-hidden rounded-3xl bg-blush-100 px-6 py-12 sm:px-10 sm:py-16 lg:px-16 lg:py-20">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-blush-600 shadow-sm">
                <x-shop.icon name="heart" class="size-4 shrink-0 fill-current" />
                Welcome to Goods Essentials
            </span>

            <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-ink sm:text-5xl lg:text-6xl">
                Little Things,
                <span class="text-blush-600">Big Smiles.</span>
            </h1>

            <p class="mt-5 max-w-xl text-base leading-8 text-muted sm:text-lg">
                Goods Essentials is your one-stop destination for baby clothing, feeding essentials,
                baby care, toys and gift sets for moms across Pakistan — at wholesale-rate pricing,
                without compromising on quality.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('shop') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-full bg-blush-600 px-7 py-3.5 text-sm font-bold text-white shadow-sm transition hover:brightness-90">
                    Explore Our Shop
                    <x-shop.icon name="arrow-right" class="size-4 shrink-0" />
                </a>

                <a href="#our-story"
                   class="inline-flex items-center justify-center rounded-full border border-blush-100 bg-white px-7 py-3.5 text-sm font-bold text-muted transition hover:border-blush-600 hover:text-blush-600">
                    Our Story
                </a>
            </div>
        </div>

        {{-- Decorative shapes --}}
        <div class="pointer-events-none absolute -right-12 -top-12 -z-0 h-56 w-56 rounded-full bg-blush-100 sm:h-80 sm:w-80"></div>
        <div class="pointer-events-none absolute -bottom-16 right-20 -z-0 h-40 w-40 rounded-full bg-lilac-100 sm:h-52 sm:w-52"></div>

        <div class="pointer-events-none absolute right-10 top-10 hidden rotate-12 text-5xl text-blush-100 sm:block">
            <x-shop.icon name="heart" class="size-4 shrink-0 fill-current" />
        </div>
        <div class="pointer-events-none absolute bottom-10 right-20 hidden -rotate-12 text-4xl text-lilac-100 sm:block">
            <x-shop.icon name="star" class="size-4 shrink-0 fill-current" />
        </div>
    </section>


    {{-- Our story --}}
    <section id="our-story" class="grid grid-cols-1 items-center gap-8 md:grid-cols-2 lg:gap-14">

        <div class="relative">
            <div class="overflow-hidden rounded-3xl bg-blush-100 p-3 sm:p-5">
                <img
                    src="{{ asset('assets/img/about.jpg') }}"
                    alt="Discover the Goods Essentials shopping experience"
                    class="h-72 w-full rounded-2xl object-cover sm:h-96"
                    loading="lazy"
                >
            </div>

            <div class="relative mx-4 -mt-8 rounded-2xl border border-line bg-white p-5 shadow-lg sm:mx-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blush-100 text-blush-600">
                        <x-shop.icon name="heart" class="size-5 shrink-0 fill-current" />
                    </div>
                    <div>
                        <h3 class="font-bold text-ink">Shopping Made Special</h3>
                        <p class="mt-1 text-sm text-muted">
                            Products for your everyday needs.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <span class="inline-flex rounded-full bg-lilac-100 px-4 py-2 text-xs font-bold uppercase tracking-wider text-lilac-600">
                Get to Know Us
            </span>

            <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-ink sm:text-4xl">
                Combining Years of
                <span class="text-blush-600">Hard Work</span>
            </h2>

            <p class="text-sm leading-7 text-muted sm:text-base">
                At Goods Essentials, we've combined years of dedication and passion to bring you a diverse range of products that cater to your everyday needs, at wholesale-level pricing. Our journey is built on hard work and a commitment to quality and affordability, ensuring that every item we offer meets our standards without the usual retail markup.
            </p>

            <p class="text-sm leading-7 text-muted sm:text-base">
                From newborn clothing to feeding essentials, baby care and gift sets, we focus on one thing: making it easy for Pakistani families to find safe, quality products for their little ones.
            </p>

            <p class="text-sm leading-7 text-muted sm:text-base">
                With a focus on customer satisfaction, our team continuously works to source and deliver baby and mom essentials you can trust. Whether you're shopping for a newborn, a toddler, or a gift for a new mom, Goods Essentials is here to make your experience smooth and enjoyable.
            </p>

            <a href="{{ route('shop') }}"
               class="inline-flex items-center gap-2 font-bold text-blush-600 transition hover:gap-3">
                Discover Our Products
                <x-shop.icon name="arrow-right" class="size-4 shrink-0" />
            </a>
        </div>
    </section>


    {{-- Popular categories --}}
    <section aria-label="Popular categories" class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="text-sm font-bold uppercase tracking-wider text-blush-600">
                    Find Your Favorites
                </span>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-ink">
                    Shop by Category
                </h2>
                <p class="mt-2 text-sm leading-6 text-muted">
                    Explore something special for every part of your life.
                </p>
            </div>

            <a href="{{ route('shop') }}"
               class="inline-flex items-center gap-2 text-sm font-bold text-blush-600 hover:brightness-90">
                View All Products
                <x-shop.icon name="arrow-right" class="size-4 shrink-0" />
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            @foreach ($aboutTiles as $cat)
                <a href="{{ route('shop.category', $cat['slug']) }}"
                   class="group flex min-h-64 flex-col overflow-hidden rounded-card bg-{{ $cat['tone'] }}-100 shadow-soft transition-transform duration-300 ease-soft hover:-translate-y-1">
                    <div class="grid flex-1 place-items-center p-4">
                        @if ($cat['image'])
                            <img src="{{ $cat['image'] }}" alt="" width="200" height="200" loading="lazy"
                                 class="size-40 rounded-img bg-white/60 object-contain p-2 mix-blend-multiply transition-transform duration-500 ease-soft group-hover:scale-105">
                        @else
                            <x-shop.icon :name="$cat['icon']" class="size-16 text-{{ $cat['tone'] }}-600" />
                        @endif
                    </div>
                    <div class="bg-white/70 p-5">
                        <span class="mb-3 inline-flex size-10 items-center justify-center rounded-full bg-white text-{{ $cat['tone'] }}-600">
                            <x-shop.icon :name="$cat['icon']" class="size-5" />
                        </span>
                        <h3 class="text-xl font-semibold text-ink">{{ $cat['label'] }}</h3>
                        <p class="mt-1 text-sm text-muted">{{ $cat['text'] ?? ($cat['count'] . ' products') }}</p>
                        <span class="mt-3 inline-flex items-center gap-2 text-sm font-extrabold text-{{ $cat['tone'] }}-600">
                            Explore Category
                            <x-shop.icon name="arrow-right" class="size-4 transition-transform group-hover:translate-x-1" />
                        </span>
                    </div>
                </a>
            @endforeach

        </div>
    </section>


    {{-- Why shop with us --}}
    <section class="rounded-3xl bg-lilac-100 px-5 py-10 sm:px-10 sm:py-12">
        <div class="mx-auto max-w-2xl text-center">
            <span class="text-sm font-bold uppercase tracking-wider text-lilac-600">
                The Goods Essentials Experience
            </span>
            <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-ink">
                Why Shop With Us?
            </h2>
            <p class="mt-3 text-sm leading-6 text-muted">
                We want every visit to our store to be simple, enjoyable, and convenient.
            </p>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mint-100 text-mint-600">
                    <x-shop.icon name="tags" class="size-6 shrink-0" />
                </div>
                <h3 class="mt-4 font-bold text-ink">Wholesale Pricing</h3>
                <p class="mt-2 text-sm leading-6 text-muted">
                    Direct-to-customer, wholesale-rate pricing on every product — without compromising on quality.
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blush-100 text-blush-600">
                    <x-shop.icon name="package" class="size-6 shrink-0" />
                </div>
                <h3 class="mt-4 font-bold text-ink">A Variety of Products</h3>
                <p class="mt-2 text-sm leading-6 text-muted">
                    Explore products across categories for your home, family, and everyday needs.
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-lilac-100 text-lilac-600">
                    <x-shop.icon name="star" class="size-6 shrink-0 fill-current" />
                </div>
                <h3 class="mt-4 font-bold text-ink">Quality Focused</h3>
                <p class="mt-2 text-sm leading-6 text-muted">
                    We aim to offer products that meet our commitment to quality.
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-sun-100 text-sun-600">
                    <x-shop.icon name="smile" class="size-6 shrink-0" />
                </div>
                <h3 class="mt-4 font-bold text-ink">Customer Satisfaction</h3>
                <p class="mt-2 text-sm leading-6 text-muted">
                    We strive to make your shopping experience smooth and enjoyable.
                </p>
            </div>
        </div>
    </section>


    {{-- Testimonials (real reviews) --}}
    <x-shop.testimonials :reviews="$testimonials" />


    {{-- Call to action --}}
    <section class="rounded-3xl bg-blush-600 px-6 py-10 text-center sm:px-10 sm:py-12">
        <div class="mx-auto max-w-2xl">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white/20 text-white">
                <x-shop.icon name="bag" class="size-6 shrink-0" />
            </div>

            <h2 class="mt-4 text-3xl font-extrabold text-white">
                Ready to Find Your Favorites?
            </h2>

            <p class="mt-3 text-sm leading-7 text-white/90 sm:text-base">
                Explore our collections and discover products for your everyday needs.
            </p>

            <a href="{{ route('shop') }}"
               class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-blush-600 transition hover:bg-blush-100">
                Start Shopping
                <x-shop.icon name="arrow-right" class="size-4 shrink-0" />
            </a>
        </div>
    </section>


    {{-- Trust bar --}}
    <x-shop.trust-bar />

</div>
@endsection