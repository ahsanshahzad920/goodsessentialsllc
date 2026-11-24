@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">
        <h3 class="heading">Reviews</h3>

        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
        @endif

        <details class="card border-0 mt-3" @if ($errors->any()) open @endif>
            <summary class="card-body py-2 fw-bold" style="cursor:pointer">+ Add a review you received (WhatsApp, phone, Instagram…)</summary>
            <form action="{{ route('admin.reviews.store') }}" method="POST" class="card-body pt-0">
                @csrf
                <p class="small text-muted mb-3">Only enter real reviews from real customers, in their own words. It goes live immediately. The name is shown as first name + last initial.</p>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Customer name *</label><input name="name" class="form-control" required maxlength="60" value="{{ old('name') }}"></div>
                    <div class="col-md-4"><label class="form-label">City</label><input name="city" class="form-control" maxlength="60" value="{{ old('city') }}"></div>
                    <div class="col-md-4"><label class="form-label">Rating *</label>
                        <select name="rating" class="form-select" required>
                            @for ($i = 5; $i >= 1; $i--) <option value="{{ $i }}" @selected(old('rating', 5) == $i)>{{ $i }} ★</option> @endfor
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label">Review *</label><textarea name="body" class="form-control" rows="3" required minlength="10" maxlength="1500">{{ old('body') }}</textarea></div>
                    <div class="col-12 form-check ms-2">
                        <input type="hidden" name="is_verified" value="0">
                        <input type="checkbox" name="is_verified" value="1" id="rv-verified" class="form-check-input" @checked(old('is_verified'))>
                        <label for="rv-verified" class="form-check-label">This person ordered from us (show "Verified buyer")</label>
                    </div>
                    <div class="col-12"><button class="btn btn-primary">Add review</button></div>
                </div>
            </form>
        </details>

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
                                    @if ($review->is_featured) <span class="badge bg-info text-dark">Pinned</span> @endif
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
                                        <button class="btn btn-sm btn-outline-info">{{ $review->is_featured ? 'Unpin' : 'Pin to top of homepage' }}</button>
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
