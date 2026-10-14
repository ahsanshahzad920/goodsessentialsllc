@extends('layouts.guest')

@section('content')
    <!-- Contact Hero -->
    <section class="relative overflow-hidden bg-gradient-to-r from-pink-100 via-purple-50 to-blue-100 py-16 md:py-24"
        id="hero">
        <div class="container mx-auto px-4 text-center">
            <span class="mb-4 inline-block rounded-full bg-white px-5 py-2 text-sm font-semibold text-pink-600 shadow-sm">
                We'd Love to Hear From You
            </span>

            <h1 class="mb-4 text-4xl font-extrabold text-gray-900 md:text-6xl">
                Contact <span class="text-pink-500">Us</span>
            </h1>

            <p class="mx-auto max-w-2xl text-base leading-7 text-gray-600 md:text-lg">
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
                <div class="rounded-2xl border border-pink-100 bg-pink-50 p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-pink-100 text-pink-600">
                        <i class="fa-solid fa-phone text-2xl"></i>
                    </div>

                    <h3 class="mb-2 text-xl font-bold text-gray-900">
                        Call Us Anytime
                    </h3>

                    <a href="tel:+18325186347"
                        class="text-gray-600 transition hover:text-pink-600">
                        +1 (832) 518-6347
                    </a>
                </div>

                <!-- Email -->
                <div class="rounded-2xl border border-purple-100 bg-purple-50 p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                        <i class="fa-solid fa-envelope text-2xl"></i>
                    </div>

                    <h3 class="mb-2 text-xl font-bold text-gray-900">
                        Send Us an Email
                    </h3>

                    <a href="mailto:megamerchantinc01@gmail.com"
                        class="break-all text-gray-600 transition hover:text-purple-600">
                        megamerchantinc01@gmail.com
                    </a>
                </div>

                <!-- Address -->
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i class="fa-solid fa-location-dot text-2xl"></i>
                    </div>

                    <h3 class="mb-2 text-xl font-bold text-gray-900">
                        Our Address
                    </h3>

                    <p class="text-gray-600">
                        811 Avenue D #170, Katy, TX 7749
                    </p>
                </div>

            </div>

            <!-- Contact Form and Map -->
            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-5">

                <!-- Form -->
                <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-lg md:p-8 lg:col-span-3">
                    <div class="mb-8">
                        <span class="mb-3 inline-block text-sm font-bold uppercase tracking-widest text-pink-500">
                            Get in Touch
                        </span>

                        <h2 class="mb-3 text-3xl font-extrabold text-gray-900 md:text-4xl">
                            Drop Us a Line
                        </h2>

                        <p class="leading-7 text-gray-600">
                            We're happy to answer your questions or help with your order.
                            Send us a message using the form below.
                        </p>
                    </div>

                    <div class="mb-4">
                        <x-alerts.error />
                    </div>

                    <x-forms.form action="{{ route('contacts.store') }}">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <!-- First Name -->
                            <div>
                                <x-forms.label for="first_name" value="First Name" />

                                <x-forms.field
                                    type="text"
                                    name="first_name"
                                    id="first_name"
                                    placeholder="John"
                                    value="{{ old('first_name') }}"
                                />

                                <x-forms.input-error :messages="$errors->get('first_name')" />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <x-forms.label for="last_name" value="Last Name" />

                                <x-forms.field
                                    type="text"
                                    name="last_name"
                                    id="last_name"
                                    placeholder="Doe"
                                    value="{{ old('last_name') }}"
                                />

                                <x-forms.input-error :messages="$errors->get('last_name')" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-forms.label for="email" value="Email Address" />

                                <x-forms.field
                                    type="email"
                                    name="email"
                                    id="email"
                                    placeholder="example@mail.com"
                                    value="{{ old('email') }}"
                                />

                                <x-forms.input-error :messages="$errors->get('email')" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-forms.label for="phone" value="Phone Number" />

                                <x-forms.field
                                    type="tel"
                                    name="phone"
                                    id="phone"
                                    placeholder="+1 234 567 890"
                                    value="{{ old('phone') }}"
                                />

                                <x-forms.input-error :messages="$errors->get('phone')" />
                            </div>

                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <x-forms.label for="address" value="Address" />

                                <x-forms.field
                                    type="text"
                                    name="address"
                                    id="address"
                                    placeholder="Enter your address"
                                    value="{{ old('address') }}"
                                />

                                <x-forms.input-error :messages="$errors->get('address')" />
                            </div>

                            <!-- Message -->
                            <div class="sm:col-span-2">
                                <x-forms.label for="note" value="Your Message" />

                                <x-forms.textarea
                                    name="note"
                                    id="note"
                                    rows="5"
                                    placeholder="Tell us how we can help..."
                                >{{ old('note') }}</x-forms.textarea>

                                <x-forms.input-error :messages="$errors->get('note')" />
                            </div>

                            <!-- Submit -->
                            <div class="sm:col-span-2">
                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center gap-3 rounded-xl bg-pink-500 px-8 py-4 font-bold text-white shadow-md transition duration-300 hover:bg-pink-600 hover:shadow-lg sm:w-auto"
                                >
                                    <i class="fa-solid fa-paper-plane"></i>
                                    Send Message
                                </button>
                            </div>

                        </div>
                    </x-forms.form>
                </div>

                <!-- Map -->
                <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-lg lg:col-span-2">
                    <div class="bg-purple-50 p-6">
                        <h3 class="mb-2 text-2xl font-bold text-gray-900">
                            Find Us Here
                        </h3>

                        <p class="text-sm leading-6 text-gray-600">
                            We'd love to hear from you. Reach out to our team
                            whenever you need assistance.
                        </p>
                    </div>

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621"
                        title="Our location on Google Maps"
                        class="h-80 w-full border-0 lg:h-[510px]"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>

                    <div class="p-6">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot mt-1 text-xl text-pink-500"></i>

                            <div>
                                <h4 class="font-bold text-gray-900">Visit Our Location</h4>
                                <p class="mt-1 text-sm leading-6 text-gray-600">
                                    811 Avenue D #170, Katy, TX 7749
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
