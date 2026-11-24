@extends('layouts.guest')

{{-- /reviews: every approved review + the store-wide "Leave a review" form (App\Http\Controllers\Frontend\StoreReviewController). --}}
@php
    \Artesaos\SEOTools\Facades\SEOMeta::setTitle('Customer Reviews');
    \Artesaos\SEOTools\Facades\SEOMeta::setDescription('Reviews from Goods Essentials customers. Bought from us? Share your experience; reviews appear after a quick check.');
@endphp

@section('content')
<div class="container-shop space-y-10 pb-16 pt-6">
    <header class="text-center">
        <p class="text-xs font-extrabold uppercase tracking-wider text-brand-600">Kind words from customers</p>
        <h1 class="mt-1 font-display text-3xl font-semibold text-ink md:text-4xl">What Our Customers Say</h1>
        @if ($reviews->total())
            <p class="mt-2 text-sm text-muted">{{ $reviews->total() }} {{ \Illuminate\Support\Str::plural('review', $reviews->total()) }} from real customers, shown after they are checked.</p>
        @endif
    </header>

    <x-shop.testimonials :reviews="$reviews->getCollection()" :empty-state="true" :header="false" :cta="false" />

    @if ($reviews->hasPages())
        <div>{{ $reviews->links('vendor.pagination.shop') }}</div>
    @endif

    {{-- Leave a review --}}
    <section id="write" aria-labelledby="write-title" class="mx-auto max-w-2xl scroll-mt-28 rounded-card border border-line bg-white p-5 shadow-sm md:p-8">
        <h2 id="write-title" class="font-display text-2xl font-semibold text-ink">Leave a Review</h2>
        <p class="mt-1 text-sm text-muted">Tell other parents about your order. Every review is checked before it appears.</p>

        @if (session('review_submitted'))
            <p class="mt-5 rounded-xl bg-mint-100 p-4 text-sm font-bold text-mint-600" role="status">
                Thank you! Your review was received and will appear here after a quick check.
            </p>
        @else
            <form action="{{ route('store-reviews.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                @if ($errors->any())
                    <p class="rounded-xl bg-coral-50 p-3 text-sm font-bold text-coral-700" role="alert">{{ $errors->first() }}</p>
                    {{-- Validation redirects drop the #write fragment; bring the form back into view. --}}
                    <script>location.hash = 'write';</script>
                @endif

                <fieldset>
                    <legend class="mb-2 text-sm font-bold text-ink">Your rating</legend>
                    {{-- DOM order 5→1, shown 1→5: ":checked ~ label" lights the chosen star and every lower one. --}}
                    <div class="rating-picker flex flex-row-reverse justify-end gap-1">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" id="store-rate-{{ $i }}" value="{{ $i }}" class="rating-input sr-only" required @checked(old('rating') == $i)>
                            <label for="store-rate-{{ $i }}" class="rating-star cursor-pointer text-line" title="{{ $i }} {{ \Illuminate\Support\Str::plural('star', $i) }}">
                                <x-shop.icon name="star" class="size-9 fill-current" /><span class="sr-only">{{ $i }} {{ \Illuminate\Support\Str::plural('star', $i) }}</span>
                            </label>
                        @endfor
                    </div>
                </fieldset>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block text-sm font-bold text-ink">Your name
                        <input name="name" required maxlength="60" autocomplete="name" value="{{ old('name', Auth::user()->first_name ?? '') }}"
                               class="mt-1 block min-h-11 w-full rounded-xl border border-line px-3 font-normal">
                        <span class="mt-1 block text-xs font-normal text-muted">Shown as first name + last initial, e.g. "Ayesha K."</span>
                    </label>
                    <label class="block text-sm font-bold text-ink">City <span class="font-normal text-muted">(optional)</span>
                        <input name="city" maxlength="60" autocomplete="address-level2" value="{{ old('city') }}" placeholder="e.g. Lahore"
                               class="mt-1 block min-h-11 w-full rounded-xl border border-line px-3 font-normal">
                    </label>
                </div>

                <label class="block text-sm font-bold text-ink">Your review
                    <textarea name="body" required minlength="10" maxlength="1500" rows="5"
                              class="mt-1 block w-full rounded-xl border border-line p-3 font-normal">{{ old('body') }}</textarea>
                </label>

                <label class="block text-sm font-bold text-ink">Phone number you ordered with <span class="font-normal text-muted">(optional: adds a "Verified buyer" badge; never shown)</span>
                    <input name="phone" inputmode="tel" maxlength="20" autocomplete="tel" placeholder="03XX-XXXXXXX"
                           class="mt-1 block min-h-11 w-full rounded-xl border border-line px-3 font-normal">
                </label>

                <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

                <x-button icon="star">Submit review</x-button>
            </form>
        @endif
    </section>
</div>
@endsection

@push('style')
<style>
    .rating-picker .rating-input:checked ~ .rating-star,
    .rating-picker .rating-star:hover,
    .rating-picker .rating-star:hover ~ .rating-star { color: var(--color-star); }
    .rating-picker .rating-input:focus-visible + .rating-star { outline: 2px solid var(--color-brand-600); outline-offset: 2px; border-radius: 6px; }
</style>
@endpush
