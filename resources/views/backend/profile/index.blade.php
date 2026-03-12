@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">
        <h3 class="heading">Table</h3>
        <div class="row">
            <div class="col-md-3 mt-3">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <div class="tab">
                            <button class="tablinks d-flex justify-content-between" id="defaultOpen"
                                onclick="openCity(event, 'Personal-Info')">
                                Profile Info
                                <img src="{{ !is_null($user->image) ? asset('storage/' . $user->image) : asset('dashasset/img/profile-info.svg') }}"
                                    alt="" />

                            </button>
                            <button class="tablinks d-flex justify-content-between mt-2"
                                onclick="openCity(event, 'Change-password')">
                                Change Password
                                <img src="dasheets/img/change-password.svg" alt="" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 mt-3">
                <div class="card rounded-3 border-0 card-shadow">
                    <div class="card-body">
                        <div class="row personal-info-row">
                            <div class="col-md-3 col-xxl-2">
                                <img src="{{ !is_null($user->image) ? asset('storage/' . $user->image) : asset('dashasset/img/profile-info.svg') }}"
                                    class="img-fluid w-100 rounded-circle" alt="Profile Picture" />
                            </div>
                            <div class="col-md-5 py-4">
                                <h4 class="mb-3 all-adjustment w-100 border-0">
                                    {{ $user->name ?? '' }}
                                </h4>
                                <p class="mb-1">{{ $user->phone ?? '' }}</p>
                                <p class="mb-0">{{ $user->email ?? '' }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <input type="file" class="fileInput" style="display: none" />
                                <button class="btn change-picture-btn">
                                    Change Profile Picture
                                </button>
                                <p class="remove-picture text-danger mt-2" style="cursor: pointer">
                                    Remove Profile Picture
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="Personal-Info" class="tabcontent">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- For updating data -->
                        <div class="card rounded-3 border-0 card-shadow h-100 p-3 mt-4">
                            <div class="card-body h-100">
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group fw-bold">
                                            <label>First Name</label>
                                            <input type="text" class="form-control mt-2" name="first_name"
                                                value="{{ old('first_name', $user->first_name) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group fw-bold">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control mt-2" name="last_name"
                                                value="{{ old('last_name', $user->last_name) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group fw-bold">
                                            <label>Username</label> <!-- Corrected the label to Username -->
                                            <input type="text" class="form-control mt-2" name="name"
                                                value="{{ old('name', $user->name) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group fw-bold">
                                            <label>Email</label>
                                            <input type="email" class="form-control mt-2" name="email"
                                                value="{{ old('email', $user->email) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group fw-bold">
                                            <label>Phone No</label>
                                            <input type="tel" class="form-control mt-2" name="phone"
                                                value="{{ old('phone', $user->phone) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group fw-bold">
                                            <label>Profile Image</label>
                                            <input type="file" class="form-control mt-2" name="image" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn save-btn text-white mt-3">Save</button>
                    </form>

                </div>

                <div id="Change-password" class="tabcontent">
                    <div class="card rounded-3 border-0 card-shadow h-100 p-3 mt-4">
                        <div class="card-body h-100">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <div class="form-group fw-bold">
                                        <label>Old Password</label>
                                        <input type="password" class="form-control mt-2" placeholder="Old Password" />
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group fw-bold">
                                        <label for="exampleFormControlSelect1">New Password
                                        </label>
                                        <input type="password" class="form-control mt-2" placeholder="New Password" />
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group fw-bold">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control mt-2" placeholder="Confirm Password" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn save-btn text-white mt-3">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        /* Style the tab */
        /* Style the buttons inside the tab */
        .tab button {
            /* display: block; */
            background-color: inherit;
            padding: 12px;
            width: 100%;
            border: none;
            /* outline: none; */
            text-align: left;
            cursor: pointer;
            /* transition: 0.3s; */
        }

        .tab button:hover {
            background: rgba(76, 73, 227, 0.1);
            /* border-left: 4px solid rgba(76, 73, 227, 1); */
        }

        .tab button.active {
            background-color: rgba(76, 73, 227, 0.1);
            /* border-left: 4px solid rgba(76, 73, 227, 1); */
        }
    </style>
@endpush

@push('script')
    <script>
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
@endpush
