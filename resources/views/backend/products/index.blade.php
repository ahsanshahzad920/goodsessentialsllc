@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="my-4 text-center">Products</h1>
                <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Create New Product</a>

                @if ($products->isEmpty())
                    <div class="alert alert-info text-center">No products found.</div>
                @else
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <table id="productsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        {{-- <th>Description</th> --}}
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Discounted Price</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td
                                                style="max-width: 300px; word-wrap: break-word; white-space: normal; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $product->product_title ?? 'N/A' }}
                                            </td>

                                            {{-- <td>{!! $product->product_description ?? 'N/A' !!}</td> --}}
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                            <td>${{ $product->price ?? 'N/A' }}</td>
                                            <td>${{ calculateDiscountPrice($product->price, $product->dis_price) ?? 'N/A' }}</td>
                                            <td>
                                                @if ($product->product_image)
                                                    <img src="{{ asset('storage/' . $product->product_image) }}"
                                                        alt="Product Image" width="50" height="50">
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Include jQuery and DataTables Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable();
        });
    </script>
@endpush
