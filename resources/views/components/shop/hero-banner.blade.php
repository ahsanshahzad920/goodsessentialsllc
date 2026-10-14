
<section class="kids-hero relative isolate overflow-hidden rounded-[2rem] bg-pink-100 px-6 py-12 sm:px-10 lg:px-14 lg:py-14">
    {{-- Decorative scalloped border --}}
    <div class="kids-hero-scallop kids-hero-scallop-top"></div>
    <div class="kids-hero-scallop kids-hero-scallop-bottom"></div>

    <div class="relative z-10 grid items-center gap-10 lg:grid-cols-2 lg:gap-12">

        {{-- Left content --}}
        <div class="order-2 space-y-5 text-center lg:order-1 lg:text-left">
            <div class="flex items-center justify-center gap-2 text-base font-semibold text-slate-800 lg:justify-start">
                <span>Everything for your</span>
                <svg class="h-6 w-6 text-pink-500" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/>
                </svg>
            </div>

            <h1 class="font-display text-5xl font-extrabold leading-[1.05] tracking-tight text-indigo-950 sm:text-6xl xl:text-7xl">
                Little Ones'
                <span class="mt-1 block text-pink-500">Big Smiles</span>
            </h1>

            <p class="mx-auto max-w-md text-base leading-7 text-slate-600 lg:mx-0 lg:text-lg">
                Safe, quality products for every stage of your child's journey.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3 pt-2 lg:justify-start">
                <a href="{{ route('shop') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-violet-500 px-7 py-4 font-bold text-white shadow-lg shadow-violet-200 transition hover:-translate-y-0.5 hover:bg-violet-600">
                    Shop Now
                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>

                <a href="{{ route('shop') }}"
                   class="inline-flex items-center justify-center rounded-full border-2 border-amber-300 bg-white px-7 py-3.5 font-bold text-indigo-950 transition hover:bg-amber-50">
                    Explore Deals
                </a>
            </div>
        </div>

        {{-- Right image --}}
        <div class="relative order-1 mx-auto w-full max-w-xl lg:order-2">
            <div class="relative overflow-hidden rounded-[2rem] bg-amber-400 shadow-xl shadow-pink-200/60">
                <img
                    src="{{ asset('images/teddybear.avif') }}"
                    alt="A cuddly teddy bear for your little one"
                    class="aspect-[4/3] w-full object-cover"
                >
            </div>

            {{-- Discount badge --}}
            <div class="absolute -right-2 -top-5 flex h-28 w-28 flex-col items-center justify-center rounded-full bg-violet-500 text-center text-white shadow-lg sm:-right-4 sm:h-32 sm:w-32">
                <span class="text-xs font-semibold">Up to</span>
                <span class="text-3xl font-extrabold leading-tight">40%</span>
                <span class="text-xs font-bold">OFF</span>
            </div>

            {{-- Decorative star --}}
            <span class="absolute -left-3 top-0 text-3xl text-amber-300" aria-hidden="true">★</span>
        </div>
    </div>
</section>