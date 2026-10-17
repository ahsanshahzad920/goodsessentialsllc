@extends('layouts.backend')

@section('content')
<div class="container-fluid pt-3 px-4 pb-5">

    <h3 class="heading">Profile Settings</h3>

    <div class="row">

        {{-- LEFT SIDEBAR --}}
        <div class="col-md-3 mt-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="tab">

                        <button
                            type="button"
                            class="tablinks d-flex justify-content-between"
                            id="defaultOpen"
                            onclick="openCity(event, 'Personal-Info')"
                        >
                            <span>Profile Info</span>
                        </button>

                        <button
                            type="button"
                            class="tablinks d-flex justify-content-between mt-2"
                            onclick="openCity(event, 'Change-password')"
                        >
                            <span>Change Password</span>
                        </button>

                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT CONTENT --}}
        <div class="col-md-9 mt-3">

            {{-- PROFILE HEADER --}}
            <div class="card rounded-3 border-0 card-shadow mb-4">
                <div class="card-body">

                    <div class="row personal-info-row align-items-center">

                        {{-- PROFILE IMAGE --}}
                        <div class="col-md-3 col-xxl-2 mb-3 mb-md-0">
                            <img
                                id="profilePreview"
                                src="{{ $user->image
                                    ? asset('storage/' . $user->image)
                                    : asset('dashasset/img/profile-info.svg') }}"
                                class="profile-preview"
                                alt="Profile Picture"
                            >
                        </div>

                        {{-- USER DETAILS --}}
                        <div class="col-md-5 py-3">
                            <h4 class="mb-3 all-adjustment w-100 border-0">
                                {{ $user->name ?? '' }}
                            </h4>

                            <p class="mb-1">
                                {{ $user->phone ?? '' }}
                            </p>

                            <p class="mb-0 text-break">
                                {{ $user->email ?? '' }}
                            </p>
                        </div>

                        {{-- IMAGE ACTIONS --}}
                        <div class="col-md-4 text-md-end profile-image-controls">

                            <input
                                type="file"
                                id="profileImageInput"
                                class="d-none"
                                accept="image/jpeg,image/png,image/webp"
                            >

                            <button
                                type="button"
                                id="changePictureBtn"
                                class="btn change-picture-btn"
                            >
                                Change Profile Picture
                            </button>

                            <button
                                type="button"
                                id="removePictureBtn"
                                class="btn btn-link text-danger d-block mt-2 p-0 remove-picture"
                                @if(!$user->image) disabled @endif
                            >
                                Remove Profile Picture
                            </button>

                            <div
                                id="profileImageActions"
                                class="mt-3 d-none"
                            >
                                <button
                                    type="button"
                                    id="savePictureBtn"
                                    class="btn btn-primary btn-sm"
                                >
                                    Save Picture
                                </button>

                                <button
                                    type="button"
                                    id="cancelPictureBtn"
                                    class="btn btn-outline-secondary btn-sm"
                                >
                                    Cancel
                                </button>
                            </div>

                            <p
                                id="profileImageMessage"
                                class="small mt-2 mb-0"
                                role="status"
                                aria-live="polite"
                            ></p>

                        </div>
                    </div>

                </div>
            </div>

            {{-- SEPARATE FORM FOR PROFILE IMAGE --}}
            <form
                id="profileImageForm"
                action="{{ route('profile.image.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="d-none"
            >
                @csrf

                <input
                    type="file"
                    name="image"
                    id="profileImageUpload"
                    accept="image/jpeg,image/png,image/webp"
                >

                <input
                    type="hidden"
                    name="remove_image"
                    id="removeImageFlag"
                    value="0"
                >
            </form>

            {{-- PERSONAL INFORMATION TAB --}}
            <div id="Personal-Info" class="tabcontent">

                <div class="card rounded-3 border-0 card-shadow">
                    <div class="card-body">

                        <h5 class="mb-4">Personal Information</h5>

                        <form
                            action="{{ route('profile.update') }}"
                            method="POST"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @method('PUT')

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">
                                        First Name
                                    </label>

                                    <input
                                        type="text"
                                        name="first_name"
                                        id="first_name"
                                        class="form-control @error('first_name') is-invalid @enderror"
                                        value="{{ old('first_name', $user->first_name) }}"
                                    >

                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">
                                        Last Name
                                    </label>

                                    <input
                                        type="text"
                                        name="last_name"
                                        id="last_name"
                                        class="form-control @error('last_name') is-invalid @enderror"
                                        value="{{ old('last_name', $user->last_name) }}"
                                    >

                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        Full Name
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}"
                                        required
                                    >

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        Email Address
                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}"
                                        required
                                    >

                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        Phone Number
                                    </label>

                                    <input
                                        type="text"
                                        name="phone"
                                        id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}"
                                    >

                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary mt-2">
                                Update Profile
                            </button>

                        </form>

                    </div>
                </div>

            </div>

            {{-- CHANGE PASSWORD TAB --}}
            <div id="Change-password" class="tabcontent">

                <div class="card rounded-3 border-0 card-shadow">
                    <div class="card-body">

                        <h5 class="mb-4">Change Password</h5>

                        <form
                            action="{{ route('password.update') }}"
                            method="POST"
                        >
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    Current Password
                                </label>

                                <input
                                    type="password"
                                    name="current_password"
                                    id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    autocomplete="current-password"
                                    required
                                >

                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    New Password
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    autocomplete="new-password"
                                    required
                                >

                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Confirm New Password
                                </label>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    autocomplete="new-password"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">
                                Update Password
                            </button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection


@push('style')
<style>
    .tab button {
        background-color: inherit;
        padding: 12px;
        width: 100%;
        border: none;
        text-align: left;
        cursor: pointer;
        border-radius: 8px;
        align-items: center;
        gap: 10px;
    }

    .tab button img {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
        object-fit: contain;
    }

    .tab button:hover,
    .tab button.active {
        background: rgba(76, 73, 227, 0.1);
    }

    .profile-preview {
        display: block;
        width: 120px;
        height: 120px;
        max-width: 100%;
        margin: 0 auto;
        object-fit: cover;
        object-position: center;
        background-color: #f8f9fa;
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.10);
    }

    .personal-info-row,
    .personal-info-row > div {
        min-width: 0;
    }

    .profile-image-controls {
        overflow-wrap: anywhere;
    }

    .change-picture-btn {
        max-width: 100%;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .remove-picture {
        cursor: pointer;
        text-decoration: none;
    }

    .remove-picture:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .tabcontent {
        display: none;
    }

    @media (max-width: 767.98px) {
        .personal-info-row {
            text-align: center;
        }

        .personal-info-row > div {
            width: 100%;
        }

        .profile-image-controls .change-picture-btn {
            width: 100%;
        }

        .profile-image-controls .remove-picture {
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>
@endpush


@push('script')
<script>
    function openCity(evt, cityName) {
        document.querySelectorAll('.tabcontent').forEach(function (tab) {
            tab.style.display = 'none';
        });

        document.querySelectorAll('.tablinks').forEach(function (button) {
            button.classList.remove('active');
        });

        const selectedTab = document.getElementById(cityName);

        if (selectedTab) {
            selectedTab.style.display = 'block';
        }

        if (evt && evt.currentTarget) {
            evt.currentTarget.classList.add('active');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const defaultButton = document.getElementById('defaultOpen');

        if (defaultButton) {
            defaultButton.click();
        }

        const changeButton = document.getElementById('changePictureBtn');
        const removeButton = document.getElementById('removePictureBtn');
        const fileInput = document.getElementById('profileImageInput');
        const uploadInput = document.getElementById('profileImageUpload');
        const preview = document.getElementById('profilePreview');
        const actions = document.getElementById('profileImageActions');
        const saveButton = document.getElementById('savePictureBtn');
        const cancelButton = document.getElementById('cancelPictureBtn');
        const removeFlag = document.getElementById('removeImageFlag');
        const form = document.getElementById('profileImageForm');
        const message = document.getElementById('profileImageMessage');

        if (
            !changeButton || !removeButton || !fileInput || !uploadInput ||
            !preview || !actions || !saveButton || !cancelButton ||
            !removeFlag || !form || !message
        ) {
            return;
        }

        const originalImage = preview.src;
        let previewUrl = null;

        function showMessage(text, isError = false) {
            message.textContent = text;
            message.classList.toggle('text-danger', isError);
            message.classList.toggle('text-success', !isError && text !== '');
        }

        function clearPreviewUrl() {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }
        }

        function resetImageSelection() {
            clearPreviewUrl();
            preview.src = originalImage;
            fileInput.value = '';
            uploadInput.value = '';
            removeFlag.value = '0';
            actions.classList.add('d-none');
            removeButton.disabled = !{{ $user->image ? 'false' : 'true' }};
            showMessage('');
        }

        changeButton.addEventListener('click', function () {
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            const file = fileInput.files[0];

            if (!file) {
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                fileInput.value = '';
                showMessage('Please select a JPG, PNG, or WEBP image.', true);
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                fileInput.value = '';
                showMessage('The image must not exceed 2 MB.', true);
                return;
            }

            clearPreviewUrl();
            previewUrl = URL.createObjectURL(file);
            preview.src = previewUrl;

            actions.classList.remove('d-none');
            showMessage('Preview ready. Click Save Picture to upload it.');
        });

        saveButton.addEventListener('click', function () {
            if (!fileInput.files.length) {
                showMessage('Please select an image first.', true);
                return;
            }

            // Copy the selected file to the dedicated upload form.
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(fileInput.files[0]);
            uploadInput.files = dataTransfer.files;

            removeFlag.value = '0';
            form.requestSubmit();
        });

        cancelButton.addEventListener('click', function () {
            resetImageSelection();
        });

        removeButton.addEventListener('click', function () {
            if (removeButton.disabled) {
                return;
            }

            if (!confirm('Are you sure you want to remove your profile picture?')) {
                return;
            }

            uploadInput.value = '';
            removeFlag.value = '1';
            form.requestSubmit();
        });
    });
</script>
@endpush