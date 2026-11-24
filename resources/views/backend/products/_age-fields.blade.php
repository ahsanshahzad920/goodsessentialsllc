{{-- Suitable age (months). Drives the homepage "Shop by Age" chips and the
     shop ?age= filter; leave both empty when age does not apply. --}}
@php
    $ageOptions = [0 => 'Newborn (0 m)', 1 => '1 month', 3 => '3 months', 6 => '6 months', 9 => '9 months', 12 => '1 year', 18 => '18 months', 24 => '2 years', 36 => '3 years', 48 => '4 years', 60 => '5 years'];
    $minAge = old('suggested_min_age_months', $product->suggested_min_age_months ?? null);
    $maxAge = old('suggested_max_age_months', $product->suggested_max_age_months ?? null);
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-6 fw-bold">
        <label for="suggested_min_age_months">Suitable from age</label>
        <select name="suggested_min_age_months" id="suggested_min_age_months" class="form-control">
            <option value="">— Not age-specific —</option>
            @foreach ($ageOptions as $m => $label)
                <option value="{{ $m }}" @selected($minAge !== null && $minAge !== '' && (int) $minAge === $m)>{{ $label }}</option>
            @endforeach
        </select>
        @error('suggested_min_age_months') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 fw-bold">
        <label for="suggested_max_age_months">Suitable up to age</label>
        <select name="suggested_max_age_months" id="suggested_max_age_months" class="form-control">
            <option value="">— No upper limit —</option>
            @foreach ($ageOptions as $m => $label)
                @continue($m === 0)
                <option value="{{ $m }}" @selected($maxAge !== null && $maxAge !== '' && (int) $maxAge === $m)>{{ $label }}</option>
            @endforeach
        </select>
        @error('suggested_max_age_months') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
    <small class="text-muted fw-normal">Used for "Shop by Age" on the homepage. Only fill this in when you know the right age range.</small>
</div>

{{-- Optional inventory. Empty = not tracked (no stock labels, no limits). --}}
<div class="form-group mb-4 fw-bold">
    <label for="stock_quantity">Stock quantity</label>
    <input type="number" min="0" step="1" name="stock_quantity" id="stock_quantity" class="form-control"
           placeholder="Leave empty if you don't track stock for this product"
           value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}">
    <small class="text-muted fw-normal d-block mt-1">
        When set: the store shows "Only N left" at {{ \App\Support\Stock::LOW }} or fewer, "Sold out" at 0, and every order
        reduces it automatically (cancelled orders give it back).
    </small>
    @error('stock_quantity') <div class="text-danger">{{ $message }}</div> @enderror
</div>
