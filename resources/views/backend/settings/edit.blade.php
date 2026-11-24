@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">
        <h3 class="heading">Store Settings</h3>

        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <div class="row g-3 mt-1">
            <div class="col-lg-7">
                <div class="card border-0">
                    <div class="card-body">
                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <h5 class="fw-bold mb-3">Delivery</h5>
                            <div class="form-group mb-4">
                                <label for="free_delivery_threshold" class="fw-bold">Free delivery on orders above (Rs.)</label>
                                <input type="number" min="1" step="1" name="free_delivery_threshold" id="free_delivery_threshold"
                                       class="form-control" placeholder="Leave empty = no free delivery"
                                       value="{{ old('free_delivery_threshold', $settings['free_delivery_threshold']) }}">
                                <small class="text-muted d-block mt-1">
                                    When set, checkout charges Rs. 0 delivery once the cart subtotal reaches this amount,
                                    and the announcement bar and cart show the offer. When empty, nobody is promised free delivery.
                                </small>
                                @error('free_delivery_threshold')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <h5 class="fw-bold mb-3">Announcement bar</h5>
                            <div class="form-check mb-4">
                                <input type="hidden" name="announcement_enabled" value="0">
                                <input class="form-check-input" type="checkbox" name="announcement_enabled" value="1" id="announcement_enabled"
                                       @checked(old('announcement_enabled', $settings['announcement_enabled']) == '1')>
                                <label class="form-check-label" for="announcement_enabled">
                                    Show the rotating bar at the top of the store (Cash on Delivery, returns, free-delivery offer)
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">What customers search for</h5>
                        <p class="text-muted small mb-3">From the shop search box. Terms with 0 results show products you may want to add or rename.</p>
                        @if ($topSearches->isEmpty())
                            <p class="text-muted small mb-0">No searches recorded yet.</p>
                        @else
                            <table class="table table-sm mb-0">
                                <thead><tr><th>Term</th><th class="text-end">Searches</th><th class="text-end">Results</th></tr></thead>
                                <tbody>
                                    @foreach ($topSearches as $t)
                                        <tr>
                                            <td>{{ $t->term }}</td>
                                            <td class="text-end">{{ $t->hits }}</td>
                                            <td class="text-end {{ $t->results === 0 ? 'text-danger fw-bold' : '' }}">{{ $t->results }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
