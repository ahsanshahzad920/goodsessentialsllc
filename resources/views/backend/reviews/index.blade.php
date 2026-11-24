@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">
        <h3 class="heading">Reviews</h3>

        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <div class="btn-group my-3">
            @foreach (['pending' => "Waiting for approval ($pending)", 'approved' => 'Approved', 'all' => 'All'] as $key => $label)
                <a href="{{ route('admin.reviews.index', ['filter' => $key]) }}" class="btn btn-sm {{ $filter === $key ? 'btn-primary' : 'btn-outline-primary' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="card border-0">
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr><th>Review</th><th>Product</th><th class="text-center">Rating</th><th>Status</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td style="max-width:420px">
                                    <div class="fw-bold">{{ $review->name }}@if ($review->city) <span class="text-muted fw-normal">· {{ $review->city }}</span>@endif
                                        @if ($review->is_verified) <span class="badge bg-success ms-1">Verified buyer</span> @endif
                                    </div>
                                    <div class="small text-muted">{{ $review->created_at?->format('d M Y, H:i') }}</div>
                                    <div class="mt-1">{{ $review->body }}</div>
                                    @if ($review->photo_url)
                                        <a href="{{ $review->photo_url }}" target="_blank"><img src="{{ $review->photo_url }}" alt="" style="width:72px;height:72px;object-fit:cover;border-radius:8px" class="mt-2"></a>
                                    @endif
                                </td>
                                <td>
                                    @if ($review->product)
                                        <a href="{{ route('productDetails', $review->product->slug) }}" target="_blank">{{ $review->product->product_title }}</a>
                                    @else
                                        <span class="text-muted">Store review</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $review->rating }} ★</td>
                                <td>
                                    {!! $review->is_approved ? '<span class="badge bg-success">Visible</span>' : '<span class="badge bg-warning text-dark">Pending</span>' !!}
                                    @if ($review->is_featured) <span class="badge bg-info text-dark">Homepage</span> @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                                        <button class="btn btn-sm {{ $review->is_approved ? 'btn-outline-secondary' : 'btn-success' }}">{{ $review->is_approved ? 'Hide' : 'Approve' }}</button>
                                    </form>
                                    <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="is_featured" value="{{ $review->is_featured ? 0 : 1 }}">
                                        <button class="btn btn-sm btn-outline-info">{{ $review->is_featured ? 'Remove from homepage' : 'Show on homepage' }}</button>
                                    </form>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No reviews here.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">{{ $reviews->links() }}</div>
    </div>
@endsection
