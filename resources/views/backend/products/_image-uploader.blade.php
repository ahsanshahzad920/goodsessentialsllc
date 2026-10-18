@php
    $existing = $product ? $product->images : collect();
    $mainId = old('main_image', $existing->firstWhere('path', $product?->product_image)?->id ?? $existing->first()?->id);
@endphp

<div class="form-group mb-4 fw-bold">
    <label for="images">Product Images</label>

    {{-- Images already saved (edit page) --}}
    @if ($existing->isNotEmpty())
        <div class="row g-3 mt-1">
            @foreach ($existing as $image)
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="border rounded p-2 text-center h-100 bg-white">
                        <img src="{{ $image->url }}" alt="Product image" class="img-fluid rounded mb-2"
                            style="height: 110px; width: 100%; object-fit: cover;">

                        <div class="form-check d-flex justify-content-center gap-1">
                            <input class="form-check-input" type="radio" name="main_image" id="main_{{ $image->id }}"
                                value="{{ $image->id }}" @checked((string) $mainId === (string) $image->id)>
                            <label class="form-check-label small fw-normal" for="main_{{ $image->id }}">Main</label>
                        </div>

                        <div class="form-check d-flex justify-content-center gap-1">
                            <input class="form-check-input" type="checkbox" name="remove_images[]"
                                id="remove_{{ $image->id }}" value="{{ $image->id }}"
                                @checked(in_array($image->id, array_map('intval', (array) old('remove_images', []))))>
                            <label class="form-check-label small fw-normal text-danger" for="remove_{{ $image->id }}">Remove</label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <small class="form-text text-muted fw-normal d-block mt-2 mb-3">
            Choose the main image, or tick Remove to delete an image when you save.
        </small>
    @endif

    <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple
        data-max="10" data-has-existing="{{ $existing->isNotEmpty() ? '1' : '0' }}">
    <small class="form-text text-muted fw-normal">
        You can select several images at once or add more in several rounds (max 10 per product, 10 MB each).
        @if ($existing->isEmpty()) The first image is the main image. @endif
    </small>

    @error('images')
        <div class="text-danger">{{ $message }}</div>
    @enderror
    @foreach ($errors->get('images.*') as $messages)
        @foreach ($messages as $message)
            <div class="text-danger">{{ $message }}</div>
        @endforeach
    @endforeach

    {{-- Previews of the images about to be uploaded --}}
    <div id="image-previews" class="d-flex flex-wrap gap-2 mt-3"></div>
</div>

@push('script')
    <script>
        (function() {
            var input = document.getElementById('images');
            var box = document.getElementById('image-previews');
            if (!input || !box || !window.DataTransfer) return;

            var max = parseInt(input.dataset.max, 10) || 10;
            var hasExisting = input.dataset.hasExisting === '1';
            var dt = new DataTransfer();

            function render() {
                box.innerHTML = '';

                Array.from(dt.files).forEach(function(file, index) {
                    var wrap = document.createElement('div');
                    wrap.className = 'position-relative border rounded p-1 bg-white';
                    wrap.style.width = '110px';

                    var img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.alt = file.name;
                    img.className = 'rounded';
                    img.style.cssText = 'width:100%;height:90px;object-fit:cover;';
                    img.onload = function() { URL.revokeObjectURL(img.src); };

                    var tag = document.createElement('div');
                    tag.className = 'text-center small fw-normal';
                    tag.textContent = (!hasExisting && index === 0) ? 'Main image' : 'New';

                    var remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 py-0 px-1';
                    remove.setAttribute('aria-label', 'Remove ' + file.name);
                    remove.innerHTML = '&times;';
                    remove.addEventListener('click', function() {
                        dt.items.remove(index);
                        input.files = dt.files;
                        render();
                    });

                    wrap.appendChild(img);
                    wrap.appendChild(tag);
                    wrap.appendChild(remove);
                    box.appendChild(wrap);
                });
            }

            input.addEventListener('change', function() {
                Array.from(input.files).forEach(function(file) {
                    var duplicate = Array.from(dt.files).some(function(f) {
                        return f.name === file.name && f.size === file.size && f.lastModified === file.lastModified;
                    });
                    if (!duplicate && dt.files.length < max) dt.items.add(file);
                });

                input.files = dt.files; // keep every chosen file in the form submission
                render();
            });
        })();
    </script>
@endpush
