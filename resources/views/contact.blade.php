@extends('layouts.guest')

@php
    $contact = config('shop.contact', []);
@endphp

@section('content')
    <!-- Contact Hero -->
    <section class="relative overflow-hidden bg-gradient-to-r from-blush-100 via-lilac-100 to-sky-100 py-16 md:py-24"
        id="hero">
        <div class="container mx-auto px-4 text-center">
            <span class="mb-4 inline-block rounded-full bg-white px-5 py-2 text-sm font-semibold text-blush-600 shadow-sm">
                We'd Love to Hear From You
            </span>

            <h1 class="mb-4 text-4xl font-extrabold text-ink md:text-6xl">
                Contact <span class="text-blush-600">Us</span>
            </h1>

            <p class="mx-auto max-w-2xl text-base leading-7 text-muted md:text-lg">
                Have a question about our products or your order?
                Our friendly team is always happy to help!
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="bg-white py-12 md:py-20">
        <div class="container mx-auto px-4">

            <!-- Contact Information -->
            <div class="mb-14 grid grid-cols-1 gap-6 md:grid-cols-3">

                <!-- Phone -->
                <div class="rounded-2xl border border-blush-100 bg-blush-100 p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-blush-100 text-blush-600">
                        <i class="fa-solid fa-phone text-2xl"></i>
                    </div>

                    <h3 class="mb-2 text-xl font-bold text-ink">
                        Call Us Anytime
                    </h3>

                    <a href="tel:{{ $contact['phone'] }}"
                        class="text-muted transition hover:text-blush-600">
                        {{ $contact['phone'] }}
                    </a>
                </div>

                <!-- Email -->
                <div class="rounded-2xl border border-lilac-100 bg-lilac-100 p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-lilac-100 text-lilac-600">
                        <i class="fa-solid fa-envelope text-2xl"></i>
                    </div>

                    <h3 class="mb-2 text-xl font-bold text-ink">
                        Send Us an Email
                    </h3>

                    {{-- Bug fix: this link used to point at megamerchantinc01@gmail.com
                         while the visible text said info@goodsessentialsllc.com. --}}
                    <a href="mailto:{{ $contact['email'] }}"
                        class="break-all text-muted transition hover:text-lilac-600">
                        {{ $contact['email'] }}
                    </a>
                </div>

                <!-- Address -->
                <div class="rounded-2xl border border-sky-100 bg-sky-100 p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                        <i class="fa-solid fa-location-dot text-2xl"></i>
                    </div>

                    <h3 class="mb-2 text-xl font-bold text-ink">
                        Our Address
                    </h3>

                    <p class="text-muted">
                        {{ config('shop.address') }}
                    </p>
                </div>

                <!-- Business Hours -->
                <div class="rounded-2xl border border-mint-100 bg-mint-100 p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg sm:col-span-2 lg:col-span-1">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-mint-100 text-mint-600">
                        <i class="fa-solid fa-clock text-2xl"></i>
                    </div>

                    <h3 class="mb-2 text-xl font-bold text-ink">
                        Business Hours
                    </h3>

                    <p class="text-muted">
                        @if (! empty($contact['hours']))
                            {{ $contact['hours'] }}
                        @else
                            <span class="italic text-muted">[TODO: owner to confirm business hours]</span>
                        @endif
                    </p>
                </div>

            </div>

            <!-- Contact Form and Map -->
            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-5">

                <!-- Contact Form -->
                <div class="overflow-hidden rounded-3xl border border-blush-100 bg-white shadow-xl lg:col-span-3">

                    <!-- Form Header -->
                    <div class="relative overflow-hidden bg-gradient-to-r from-blush-100 via-lilac-100 to-sky-100 px-6 py-8 md:px-9 md:py-10">
                        <div class="relative z-10">
                            <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-bold text-blush-600 shadow-sm">
                                <i class="fa-solid fa-comments"></i>
                                GET IN TOUCH
                            </div>

                            <h2 class="mb-3 text-3xl font-extrabold tracking-tight text-ink md:text-4xl">
                                Let's Talk!
                            </h2>

                            <p class="max-w-md text-sm leading-7 text-muted md:text-base">
                                Have a question about our products or an order?
                                Fill out the form and our team will get back to you.
                            </p>
                        </div>

                        <div class="pointer-events-none absolute -right-8 -top-10 h-36 w-36 rounded-full bg-blush-100/40"></div>
                        <div class="pointer-events-none absolute -bottom-12 right-20 h-28 w-28 rounded-full bg-lilac-100/40"></div>
                    </div>

                    <!-- Form Body -->
                    <div class="p-6 md:p-9">

                        <x-alerts.error />

                        <x-forms.form action="{{ route('contacts.store') }}">

                            <div class="mb-7">
                                <h3 class="text-lg font-bold text-ink">
                                    Your Contact Details
                                </h3>
                                <p class="mt-1 text-sm text-muted">
                                    Please provide your details so we can assist you.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-x-5 gap-y-6 sm:grid-cols-2">

                                <!-- First Name -->
                                <div>
                                    <x-forms.label for="first_name" value="First Name" />

                                    <div class="relative mt-2">
                                        <i class="fa-regular fa-user pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted"></i>

                                        <x-forms.field
                                            type="text"
                                            name="first_name"
                                            id="first_name"
                                            placeholder="Enter first name"
                                            value="{{ old('first_name') }}"
                                            class="w-full rounded-xl border border-line bg-mist-100 py-3.5 pl-11 pr-4 text-sm text-ink outline-none transition placeholder:text-muted focus:border-blush-600 focus:bg-white focus:ring-4 focus:ring-blush-100"
                                        />
                                    </div>

                                    <x-forms.input-error :messages="$errors->get('first_name')" />
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <x-forms.label for="last_name" value="Last Name" />

                                    <div class="relative mt-2">
                                        <i class="fa-regular fa-user pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted"></i>

                                        <x-forms.field
                                            type="text"
                                            name="last_name"
                                            id="last_name"
                                            placeholder="Enter last name"
                                            value="{{ old('last_name') }}"
                                            class="w-full rounded-xl border border-line bg-mist-100 py-3.5 pl-11 pr-4 text-sm text-ink outline-none transition placeholder:text-muted focus:border-blush-600 focus:bg-white focus:ring-4 focus:ring-blush-100"
                                        />
                                    </div>

                                    <x-forms.input-error :messages="$errors->get('last_name')" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <x-forms.label for="email" value="Email Address" />

                                    <div class="relative mt-2">
                                        <i class="fa-regular fa-envelope pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted"></i>

                                        <x-forms.field
                                            type="email"
                                            name="email"
                                            id="email"
                                            placeholder="you@example.com"
                                            value="{{ old('email') }}"
                                            class="w-full rounded-xl border border-line bg-mist-100 py-3.5 pl-11 pr-4 text-sm text-ink outline-none transition placeholder:text-muted focus:border-blush-600 focus:bg-white focus:ring-4 focus:ring-blush-100"
                                        />
                                    </div>

                                    <x-forms.input-error :messages="$errors->get('email')" />
                                </div>

                                <!-- Phone -->
                                <div>
                                    <x-forms.label for="phone" value="Phone Number" />

                                    <div class="relative mt-2">
                                        <i class="fa-solid fa-phone pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted"></i>

                                        <x-forms.field
                                            type="tel"
                                            name="phone"
                                            id="phone"
                                            placeholder="+92 300 1234567"
                                            value="{{ old('phone') }}"
                                            class="w-full rounded-xl border border-line bg-mist-100 py-3.5 pl-11 pr-4 text-sm text-ink outline-none transition placeholder:text-muted focus:border-blush-600 focus:bg-white focus:ring-4 focus:ring-blush-100"
                                        />
                                    </div>

                                    <x-forms.input-error :messages="$errors->get('phone')" />
                                </div>

                                <!-- Address -->
                                <div class="sm:col-span-2">
                                    <x-forms.label for="address" value="Address" />

                                    <div class="relative mt-2">
                                        <i class="fa-solid fa-location-dot pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted"></i>

                                        <x-forms.field
                                            type="text"
                                            name="address"
                                            id="address"
                                            placeholder="Enter your delivery or contact address"
                                            value="{{ old('address') }}"
                                            class="w-full rounded-xl border border-line bg-mist-100 py-3.5 pl-11 pr-4 text-sm text-ink outline-none transition placeholder:text-muted focus:border-blush-600 focus:bg-white focus:ring-4 focus:ring-blush-100"
                                        />
                                    </div>

                                    <x-forms.input-error :messages="$errors->get('address')" />
                                </div>

                                <!-- Message -->
                                <div class="sm:col-span-2">
                                    <x-forms.label for="note" value="Your Message" />

                                    <div class="relative mt-2">
                                        <i class="fa-regular fa-message pointer-events-none absolute left-4 top-4 text-muted"></i>

                                        <x-forms.textarea
                                            name="note"
                                            id="note"
                                            rows="5"
                                            placeholder="Tell us what you need help with..."
                                            class="w-full rounded-xl border border-line bg-mist-100 py-3.5 pl-11 pr-4 text-sm text-ink outline-none transition placeholder:text-muted focus:border-blush-600 focus:bg-white focus:ring-4 focus:ring-blush-100"
                                        >{{ old('note') }}</x-forms.textarea>
                                    </div>

                                    <x-forms.input-error :messages="$errors->get('note')" />
                                </div>

                                <!-- Submit -->
                                <div class="sm:col-span-2 pt-2">
                                    <button
                                        type="submit"
                                        class="group inline-flex w-full items-center justify-center gap-3 rounded-xl bg-blush-600 px-8 py-4 font-bold text-white shadow-lg shadow-blush-100 transition duration-300 hover:-translate-y-0.5 hover:brightness-90 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blush-100 sm:w-auto"
                                    >
                                        <span>Send Message</span>
                                        <i class="fa-solid fa-paper-plane transition-transform duration-300 group-hover:translate-x-1"></i>
                                    </button>

                                    <p class="mt-4 flex items-center gap-2 text-xs leading-5 text-muted">
                                        <i class="fa-solid fa-lock text-blush-600"></i>
                                        Your contact details will only be used to respond to your inquiry.
                                    </p>
                                </div>

                            </div>
                        </x-forms.form>
                    </div>
                </div>

                <!-- Map -->
                <div class="overflow-hidden rounded-3xl border border-line bg-white shadow-xl lg:col-span-2">

                    <div class="bg-gradient-to-r from-lilac-100 to-sky-100 p-6 md:p-7">
                        <span class="mb-3 inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-blush-600">
                            <i class="fa-solid fa-map-location-dot"></i>
                            Our Location
                        </span>

                        <h3 class="mb-2 text-2xl font-extrabold text-ink">
                            Find Us Here
                        </h3>

                        <p class="text-sm leading-6 text-muted">
                            Need help with an order? Contact our team or find our location below.
                        </p>
                    </div>

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1461.2844368167343!2d71.67079331901073!3d29.41301872652528!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1792066303105!5m2!1sen!2s"
                        title="Our location on Google Maps"
                        class="h-80 w-full border-0 lg:h-[510px]"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>

                    <div class="p-6 md:p-7">
                        <div class="flex items-start gap-4 rounded-2xl border border-blush-100 bg-blush-100 p-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blush-100 text-blush-600">
                                <i class="fa-solid fa-location-dot text-xl"></i>
                            </div>

                            <div>
                                <h4 class="font-bold text-ink">Visit Our Location</h4>
                                <p class="mt-1 text-sm leading-6 text-muted">
                                    {{ config('shop.address') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@if (session('success'))
    @push('script')
    <script>
        // TikTok Contact: fires once (Laravel flash data only survives the
        // single request right after redirect, so a refresh won't re-fire).
        ttqTrack('Contact', { content_name: 'contact_form' });
    </script>
    @endpush
@endif
