@extends('layouts.guest')

@section('content')
<div class="bg-brand-50/40 py-10 sm:py-14">
    <div class="container-shop mx-auto max-w-5xl px-4">

        {{-- Heading --}}
        <div class="mb-8">
            <p class="mb-2 text-sm font-bold uppercase tracking-wider text-brand-500">
                My Account
            </p>
            <h1 class="text-3xl font-extrabold text-ink sm:text-4xl">
                My Profile
            </h1>
            <p class="mt-2 text-sm text-muted">
                Manage your personal information and account security.
            </p>
        </div>

        {{-- Success message --}}
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="mb-2 font-bold">Please correct the following errors:</p>
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 md:grid-cols-3">

            {{-- Profile summary --}}
            <aside class="h-fit rounded-2xl border border-line bg-white p-6 shadow-sm">
                <div class="flex flex-col items-center">
                    @if ($user->image)
                        <img
                            src="{{ asset('storage/' . $user->image) }}"
                            alt="{{ $user->name }}'s profile image"
                            class="size-28 rounded-full border-4 border-brand-50 object-cover"
                        >
                    @else
                        <div class="grid size-28 place-items-center rounded-full bg-brand-50 text-brand-500">
                            <x-shop.icon name="user" class="size-14" />
                        </div>
                    @endif

                    <h2 class="mt-4 break-words text-center text-xl font-bold text-ink">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-1 break-all text-center text-sm text-muted">
                        {{ $user->email }}
                    </p>

                    @if ($user->phone)
                        <p class="mt-2 text-sm text-muted">
                            {{ $user->phone }}
                        </p>
                    @endif
                </div>

                <div class="mt-6 border-t border-line pt-5">
                    <a
                        href="{{ route('shop') }}"
                        class="block rounded-full border border-line px-4 py-3 text-center text-sm font-bold text-ink transition hover:bg-brand-50"
                    >
                        Continue Shopping
                    </a>
                </div>
            </aside>

            {{-- Profile form --}}
            <section class="rounded-2xl border border-line bg-white p-6 shadow-sm sm:p-8 md:col-span-2">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-ink">
                        Personal Information
                    </h2>
                    <p class="mt-1 text-sm text-muted">
                        Update your details and profile picture.
                    </p>
                </div>

                <form
                    action="{{ route('user.profile.update') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-5"
                >
                    @csrf
                    @method('PUT')

                    {{-- Profile image --}}
                    <div>
                        <label for="image" class="mb-2 block text-sm font-semibold text-ink">
                            Profile Picture
                        </label>

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            @if ($user->image)
                                <img
                                    src="{{ asset('storage/' . $user->image) }}"
                                    alt="Current profile picture"
                                    class="size-16 rounded-full object-cover"
                                >
                            @else
                                <div class="grid size-16 place-items-center rounded-full bg-brand-50 text-brand-500">
                                    <x-shop.icon name="user" class="size-8" />
                                </div>
                            @endif

                            <input
                                id="image"
                                name="image"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="block w-full rounded-xl border border-line bg-white text-sm text-ink file:mr-4 file:border-0 file:bg-brand-50 file:px-4 file:py-3 file:font-semibold file:text-brand-600 hover:file:bg-brand-100"
                            >
                        </div>

                        <p class="mt-2 text-xs text-muted">
                            JPG, JPEG, PNG or WEBP. Maximum file size: 2 MB.
                        </p>

                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Name --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-ink">
                            Full Name
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            required
                            autocomplete="name"
                            class="w-full rounded-xl border border-line px-4 py-3 text-sm text-ink outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-ink">
                            Email Address
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            autocomplete="email"
                            class="w-full rounded-xl border border-line px-4 py-3 text-sm text-ink outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                        >

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-semibold text-ink">
                            Phone Number
                        </label>

                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            value="{{ old('phone', $user->phone) }}"
                            autocomplete="tel"
                            placeholder="Enter your phone number"
                            class="w-full rounded-xl border border-line px-4 py-3 text-sm text-ink outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                        >

                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="border-t border-line pt-5">
                        <h3 class="text-lg font-bold text-ink">
                            Change Password
                        </h3>
                        <p class="mt-1 text-sm text-muted">
                            Leave the password fields empty if you don't want to change your password.
                        </p>
                    </div>

                    <div>
                        <label for="current_password" class="mb-2 block text-sm font-semibold text-ink">
                            Current Password
                        </label>

                        <input
                            id="current_password"
                            name="current_password"
                            type="password"
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-line px-4 py-3 text-sm text-ink outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                        >

                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-ink">
                            New Password
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-line px-4 py-3 text-sm text-ink outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                        >

                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-ink">
                            Confirm New Password
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-line px-4 py-3 text-sm text-ink outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                        >
                    </div>

                    {{-- Save --}}
                    <div class="border-t border-line pt-5">
                        <button
                            type="submit"
                            class="w-full rounded-full bg-brand-500 px-6 py-3 font-bold text-white transition hover:bg-brand-600 sm:w-auto"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </section>

        </div>
    </div>
</div>
@endsection