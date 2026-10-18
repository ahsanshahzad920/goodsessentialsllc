@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">
        <h3 class="heading">Edit Product</h3>

        <div class="card border-0 mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT') {{-- This is important for updating the resource --}}

                            {{-- Product Title --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="product_title">Product Title</label>
                                <input type="text" name="product_title" class="form-control"
                                    value="{{ old('product_title', $product->product_title) }}" required>
                                @error('product_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Product Description --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="product_description">Product Description</label>
                                <textarea name="product_description" class="form-control editor" rows="6">{{ old('product_description', $product->product_description) }}</textarea>
                                @error('product_description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Product Price --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="price">Product Price</label>
                                <input type="number" name="price" class="form-control"
                                    value="{{ old('price', $product->price) }}" step="any" required>
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Product Dis Price --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="dis_price">Product Discounted Price %</label>
                                <input type="number" name="dis_price" class="form-control"
                                    value="{{ old('dis_price', $product->dis_price) }}" step="any" required>
                                @error('dis_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Select Category --}}
                            <div class="form-group mb-4 fw-bold">
                                <label for="category_id">Category</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="" disabled>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Product color picker --}}
                                @include('backend.products._color-picker', ['product' => $product])
                            {{-- Product Images (current + add more) --}}
                            @include('backend.products._image-uploader', ['product' => $product])

                            <button type="submit" class="btn btn-success">Update Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
