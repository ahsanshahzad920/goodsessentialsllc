@php
    $current = collect(old('colors', $product?->colors ?? []))->values()->all();

    $presets = [
        ['Black', '#111827'], ['White', '#ffffff'], ['Red', '#ef4444'], ['Pink', '#ec4899'],
        ['Purple', '#8b5cf6'], ['Blue', '#3b82f6'], ['Navy', '#1e3a8a'], ['Green', '#22c55e'],
        ['Yellow', '#facc15'], ['Orange', '#f97316'], ['Brown', '#92400e'], ['Grey', '#9ca3af'],
    ];
@endphp

<div class="form-group mb-4 fw-bold">
    <label>Available Colors <span class="fw-normal text-muted">(optional)</span></label>
    <small class="d-block text-muted fw-normal mb-2">
        Customers choose one of these colors with radio buttons on the product page. Leave empty if the product has no color options (max 12).
    </small>

    {{-- Quick add --}}
    <div id="color-presets" class="d-flex flex-wrap gap-2 mb-3">
        @foreach ($presets as [$presetName, $presetHex])
            <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2"
                data-name="{{ $presetName }}" data-hex="{{ $presetHex }}">
                <span style="display:inline-block;width:16px;height:16px;border-radius:50%;background:{{ $presetHex }};border:1px solid #ccc;"></span>
                <span class="fw-normal">{{ $presetName }}</span>
            </button>
        @endforeach
    </div>

    {{-- Chosen colors --}}
    <div id="color-rows" class="d-flex flex-column gap-2">
        @foreach ($current as $i => $color)
            <div class="color-row d-flex align-items-center gap-2">
                <input type="color" name="colors[{{ $i }}][hex]" value="{{ $color['hex'] ?? '#000000' }}"
                    class="form-control form-control-color" title="Pick color">
                <input type="text" name="colors[{{ $i }}][name]" value="{{ $color['name'] ?? '' }}"
                    class="form-control" placeholder="Color name (e.g. Sky Blue)" maxlength="30" required style="max-width: 260px;">
                <button type="button" class="btn btn-sm btn-outline-danger remove-color" aria-label="Remove color">&times;</button>
            </div>
        @endforeach
    </div>

    <button type="button" id="add-color" class="btn btn-sm btn-outline-primary mt-2">+ Add custom color</button>

    @error('colors')
        <div class="text-danger">{{ $message }}</div>
    @enderror
    @foreach ($errors->get('colors.*') as $messages)
        @foreach ($messages as $message)
            <div class="text-danger">{{ $message }}</div>
        @endforeach
    @endforeach

    <template id="color-row-template">
        <div class="color-row d-flex align-items-center gap-2">
            <input type="color" name="colors[__I__][hex]" value="#000000" class="form-control form-control-color" title="Pick color">
            <input type="text" name="colors[__I__][name]" class="form-control" placeholder="Color name (e.g. Sky Blue)"
                maxlength="30" required style="max-width: 260px;">
            <button type="button" class="btn btn-sm btn-outline-danger remove-color" aria-label="Remove color">&times;</button>
        </div>
    </template>
</div>

@push('script')
    <script>
        (function() {
            var rows = document.getElementById('color-rows');
            var tpl = document.getElementById('color-row-template');
            if (!rows || !tpl) return;

            var next = 1000; // unique field index for rows added in the browser
            var MAX = 12;

            function addRow(name, hex) {
                if (rows.children.length >= MAX) return null;

                var holder = document.createElement('div');
                holder.innerHTML = tpl.innerHTML.replace(/__I__/g, next++).trim();
                var row = holder.firstElementChild;

                if (hex) row.querySelector('input[type="color"]').value = hex;
                if (name) row.querySelector('input[type="text"]').value = name;

                rows.appendChild(row);
                return row;
            }

            function hasHex(hex) {
                return Array.prototype.some.call(rows.querySelectorAll('input[type="color"]'), function(input) {
                    return input.value.toLowerCase() === hex.toLowerCase();
                });
            }

            document.getElementById('add-color').addEventListener('click', function() {
                var row = addRow('', '#000000');
                if (row) row.querySelector('input[type="text"]').focus();
            });

            document.getElementById('color-presets').addEventListener('click', function(e) {
                var button = e.target.closest('[data-hex]');
                if (button && !hasHex(button.dataset.hex)) addRow(button.dataset.name, button.dataset.hex);
            });

            rows.addEventListener('click', function(e) {
                var remove = e.target.closest('.remove-color');
                if (remove) remove.closest('.color-row').remove();
            });
        })();
    </script>
@endpush
