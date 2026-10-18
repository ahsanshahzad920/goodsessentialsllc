@props(['colors' => [], 'name' => 'color', 'selected' => null])

@if (! empty($colors))
    @php
        $selectedHex  = strtolower((string) old($name, $selected ?? ($colors[0]['hex'] ?? '')));
        $selectedName = collect($colors)->firstWhere('hex', $selectedHex)['name'] ?? ($colors[0]['name'] ?? '');
    @endphp

    <fieldset>
        <legend class="mb-3 block text-sm font-semibold text-gray-800">
            Color: <span data-color-label class="font-bold text-brand-500">{{ $selectedName }}</span>
        </legend>

        <div class="flex flex-wrap gap-3" role="radiogroup" aria-label="Color">
            @foreach ($colors as $color)
                <label class="relative cursor-pointer" title="{{ $color['name'] }}">
                    <input type="radio" name="{{ $name }}" value="{{ $color['hex'] }}"
                           data-color-name="{{ $color['name'] }}" required
                           class="peer sr-only" @checked($selectedHex === strtolower($color['hex']))>

                    <span class="grid size-11 place-items-center rounded-full border-2 border-line p-0.5 transition duration-150 hover:scale-105 peer-checked:border-brand-500 peer-checked:ring-2 peer-checked:ring-brand-500/30 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-500/40">
                        <span class="block size-full rounded-full border border-black/10"
                              style="background-color: {{ $color['hex'] }}"></span>
                    </span>

                    <span class="sr-only">{{ $color['name'] }}</span>
                </label>
            @endforeach
        </div>

        @error($name)
            <p class="mt-2 text-xs font-semibold text-blush-600">{{ $message }}</p>
        @enderror
    </fieldset>

    @pushOnce('script')
    <script>
        document.addEventListener('change', function (e) {
            var radio = e.target;
            if (!radio.matches || !radio.matches('input[type="radio"][data-color-name]')) return;

            var label = radio.closest('fieldset').querySelector('[data-color-label]');
            if (label) label.textContent = radio.dataset.colorName;
        });
    </script>
    @endPushOnce
@endif
