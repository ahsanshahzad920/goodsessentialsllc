@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">
        <h3 class="heading">Edit Category</h3>

        <div class="card border-0 mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') <!-- Specify the method as PUT for updating -->

                            <div class="form-group mb-4 fw-bold">
                                <label for="name">Category Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $category->name) }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4 fw-bold">
                                <label for="description">Category Description</label>
                                <textarea name="description" class="form-control" rows="6">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4 fw-bold">
                                <label for="image">Homepage Tile Image <span class="fw-normal text-muted">(optional — leave empty to auto-use the best-selling product photo)</span></label>
                                @if ($category->image)
                                    <div class="my-2">
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="" style="width:96px;height:96px;object-fit:contain;background:#FFF7F2;border-radius:12px">
                                    </div>
                                    <div class="form-check fw-normal mb-2">
                                        <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                                        <label class="form-check-label" for="remove_image">Remove current image</label>
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success">Update Category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
