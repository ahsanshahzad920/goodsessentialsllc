{{--
    "What Our Customers Say": real, admin-approved reviews only (Review::showcase()).
    Nothing here is ever invented: with no approved reviews it shows the
    new-store message (when :empty-state="true") or renders nothing.

    <x-shop.testimonials :reviews="$testimonials" :empty-state="true" />   homepage
    <x-shop.testimonials :reviews="$testimonials" />                         About page
    <x-shop.testimonials :reviews="$reviews" :header="false" :cta="false" /> /reviews grid
--}}
@props(['reviews', 'emptyState' => false, 'header' => true, 'cta' => true])

@php $tones = ['blush', 'lilac', 'sun', 'mint', 'sky']; @endphp

@if ($reviews->isNotEmpty() || $emptyState)
    <section aria-labelledby="testimonials-title" {{ $attributes->class('space-y-6') }}>
        @if ($header)
            <x-section-header id="testimonials-title" align="center" eyebrow="Kind words from customers"
                              title="What Our Customers Say"
                              :subtitle="$reviews->isNotEmpty() ? 'Real reviews from customers, shown after they are checked.' : null" />
        @endif

        @if ($reviews->isNotEmpty())
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-5">
                @foreach ($reviews as $review)
                    @php $tone = $tones[$loop->index % count($tones)]; @endphp
                    <figure class="card flex flex-col p-6">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <x-rating-stars :rating="$review->rating" size="md" />
                            @if ($review->is_verified)
                                <x-badge tone="success"><x-shop.icon name="check" class="size-3.5" /> Verified buyer</x-badge>
                            @endif
                        </div>
                        <span class="sr-only">Rated {{ $review->rating }} out of 5.</span>

                        <x-shop.icon name="quote" class="mt-4 size-7 fill-current text-{{ $tone }}-100" />
                        <blockquote class="mt-2 flex-1 whitespace-pre-line text-sm leading-7 text-ink/80">{{ $review->body }}</blockquote>

                        @if ($review->product)
                            <a href="{{ route('productDetails', $review->product->slug) }}" class="mt-4 flex items-center gap-2.5 rounded-2xl bg-frame p-2 text-xs font-bold text-ink hover:bg-coral-50">
                                <img src="{{ \App\Support\WebpImage::fit($review->product->product_image, 240) }}" alt="" width="40" height="40" loading="lazy" class="size-10 rounded-xl bg-white object-contain p-0.5">
                                <span class="line-clamp-2">{{ $review->product->product_title }}</span>
                            </a>
                        @endif

                        <figcaption class="mt-6 flex items-center gap-3 border-t border-line pt-5">
                            @if ($review->photo_url)
                                <img src="{{ \App\Support\WebpImage::fit($review->photo, 240) }}" alt="" width="44" height="44" loading="lazy" class="size-11 rounded-full object-cover">
                            @else
                                <span class="grid size-11 shrink-0 place-items-center rounded-full bg-{{ $tone }}-100 font-bold text-{{ $tone }}-600" aria-hidden="true">{{ $review->initials }}</span>
                            @endif
                            <span class="min-w-0">
                                <span class="block font-display text-sm font-semibold text-ink">{{ $review->short_name }}</span>
                                <span class="mt-0.5 block text-xs text-muted">
                                    {{ $review->city ? $review->city . ' · ' : '' }}<time datetime="{{ $review->created_at?->toDateString() }}">{{ $review->created_at?->format('M Y') }}</time>
                                </span>
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>

            @if ($cta)
                <div class="flex flex-wrap justify-center gap-3">
                    <x-button :href="route('store-reviews.index')" variant="secondary" :block="false">Read all reviews</x-button>
                    <x-button :href="route('store-reviews.index') . '#write'" variant="ghost" icon="star" :block="false">Leave a review</x-button>
                </div>
            @endif
        @else
            {{-- No approved reviews yet: say so honestly. --}}
            <div class="mx-auto max-w-xl rounded-card bg-lilac-100/60 p-6 text-center md:p-8">
                <x-shop.icon name="star" class="mx-auto size-8 fill-current text-star" />
                <p class="mt-3 text-base font-bold leading-7 text-ink">We're a new store — be one of our first customers and your review will appear here.</p>
                <div class="mt-5 flex flex-wrap justify-center gap-3">
                    <x-button :href="route('shop')" icon="bag" :block="false">Shop Now</x-button>
                    @if ($cta)
                        <x-button :href="route('store-reviews.index') . '#write'" variant="secondary" :block="false">Leave a review</x-button>
                    @endif
                </div>
            </div>
        @endif
    </section>
@endif
