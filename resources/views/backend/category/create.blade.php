@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">
        <h3 class="heading">Create Category</h3>

        <div class="card border-0 mt-3">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf


                            <div class="form-group mb-4 fw-bold">
                                <label for="name">Category Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4 fw-bold">
                                <label for="description">Category Description</label>
                                <textarea name="description" class="form-control" rows="6">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success">Create Category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
