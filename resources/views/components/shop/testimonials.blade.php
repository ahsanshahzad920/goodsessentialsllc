{{--
    Real customer reviews (approved + featured rows of the reviews table).
    Renders nothing when there are none. Used on the homepage and About page.
    <x-shop.testimonials :reviews="$testimonials" />
--}}
@props(['reviews'])

@if ($reviews->isNotEmpty())
    @php $tones = ['blush', 'lilac', 'sun', 'mint', 'sky']; @endphp
    <section aria-labelledby="testimonials-title" {{ $attributes->class('space-y-6') }}>
        <x-section-header id="testimonials-title" align="center" eyebrow="Kind words from customers"
                          title="What Our Customers Say"
                          subtitle="Feedback shared by customers about their shopping experiences." />

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-5">
            @foreach ($reviews as $review)
                @php $tone = $tones[$loop->index % count($tones)]; @endphp
                <figure class="card flex flex-col p-6 transition-transform duration-300 ease-soft hover:-translate-y-1">
                    <x-rating-stars :rating="$review->rating" size="md" />
                    <x-shop.icon name="quote" class="mt-4 size-7 fill-current text-{{ $tone }}-100" />
                    <blockquote class="mt-2 flex-1 text-sm leading-7 text-muted">{{ $review->body }}</blockquote>

                    <figcaption class="mt-6 flex items-center gap-3 border-t border-line pt-5">
                        @if ($review->photo_url)
                            <img src="{{ $review->photo_url }}" alt="" width="44" height="44" loading="lazy" class="size-11 rounded-full object-cover">
                        @else
                            <span class="grid size-11 shrink-0 place-items-center rounded-full bg-{{ $tone }}-100 font-bold text-{{ $tone }}-600" aria-hidden="true">{{ $review->initials }}</span>
                        @endif
                        <span>
                            <span class="block font-display text-sm font-semibold text-ink">{{ $review->display_name }}</span>
                            @if ($review->city)
                                <span class="mt-0.5 block text-xs text-muted">{{ $review->city }}</span>
                            @endif
                        </span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
@endif
