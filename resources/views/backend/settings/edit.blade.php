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

                            <h5 class="fw-bold mb-1">Homepage hero slides</h5>
                            <p class="text-muted small mb-3">Up to 3 products. Price, discount and photo come from the product itself. Leave a slide's product empty to remove it; leave all empty to use the defaults (Character swaddle, top winter deal, gift set).</p>
                            @foreach ($heroSlides as $i => $slide)
                                <div class="border rounded p-3 mb-3">
                                    <div class="fw-bold mb-2">Slide {{ $i + 1 }}</div>
                                    <select name="hero[{{ $i }}][product_id]" class="form-control mb-2">
                                        <option value="">— No slide —</option>
                                        @foreach ($products as $p)
                                            <option value="{{ $p->id }}" @selected((int) old("hero.$i.product_id", $slide['product_id'] ?? 0) === $p->id)>
                                                {{ $p->product_title }}{{ (float) $p->dis_price > 0 ? ' (' . (float) $p->dis_price . '% off)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="row g-2">
                                        <div class="col-md-4"><input type="text" name="hero[{{ $i }}][eyebrow]" maxlength="40" class="form-control" placeholder="Small label, e.g. Winter collection" value="{{ old("hero.$i.eyebrow", $slide['eyebrow'] ?? '') }}"></div>
                                        <div class="col-md-3"><input type="text" name="hero[{{ $i }}][badge]" maxlength="20" class="form-control" placeholder="Badge, e.g. MEGA SALE" value="{{ old("hero.$i.badge", $slide['badge'] ?? '') }}"></div>
                                        <div class="col-md-5"><input type="text" name="hero[{{ $i }}][headline]" maxlength="80" class="form-control" placeholder="Headline (default: product name)" value="{{ old("hero.$i.headline", $slide['headline'] ?? '') }}"></div>
                                    </div>
                                </div>
                            @endforeach

                            <h5 class="fw-bold mb-1 mt-4">Deals of the Week</h5>
                            <div class="form-group mb-4">
                                <label for="deals_ends_at" class="fw-bold">Deal ends at</label>
                                <input type="datetime-local" name="deals_ends_at" id="deals_ends_at" class="form-control"
                                       value="{{ old('deals_ends_at', $settings['deals_ends_at'] ? \Illuminate\Support\Carbon::parse($settings['deals_ends_at'])->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted d-block mt-1">Shows a live countdown on the homepage. When this time passes, the Deals section hides itself until you set a new date. Leave empty for deals without a countdown.</small>
                                @error('deals_ends_at') <div class="text-danger">{{ $message }}</div> @enderror
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
