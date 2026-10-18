@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">
        <h3 class="heading">Create Product</h3>

        <div class="card border-0 mt-3">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Product Title --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="product_title">Product Title</label>
                                <input type="text" name="product_title" class="form-control"
                                    value="{{ old('product_title') }}" required>
                                @error('product_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Product Description --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="product_description">Product Description</label>
                                <textarea name="product_description" class="form-control editor" rows="6" style="font-weight: normal;">{{ old('product_description') }}</textarea>
                                @error('product_description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Product Price --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="price">Product price</label>
                                <input type="number" name="price" class="form-control" step="any" value="{{ old('price') }}"
                                    required>
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Product Dis Price --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="dis_price">Product Discounted Price %</label>
                                <input type="number" name="dis_price" class="form-control" step="any" value="{{ old('dis_price', 0) }}"
                                    required>
                                @error('dis_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Select Category --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="category_id">Category</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Product color picker --}}
                            @include('backend.products._color-picker', ['product' => null])

                            {{-- Product Images (multiple) --}}
                            @include('backend.products._image-uploader', ['product' => null])

                            <button type="submit" class="btn btn-success">Create Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
