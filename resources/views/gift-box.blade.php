@extends('layouts.guest')

@section('content')
<div class="container-shop space-y-6 pb-32 pt-6">
    <section class="relative overflow-hidden rounded-card bg-butter/60 p-6 md:p-10">
        <span class="pointer-events-none absolute -right-10 -top-10 size-48 rounded-full bg-blush/70" aria-hidden="true"></span>
        <div class="relative max-w-2xl">
            <p class="text-xs font-extrabold uppercase tracking-wider text-sun-600">Newborn gifts · Aqiqa</p>
            <h1 class="mt-1 font-display text-3xl font-semibold text-ink md:text-4xl">Build a Gift Box</h1>
            <p class="mt-2 text-sm leading-7 text-ink/75">Pick {{ $min }}–{{ $max }} essentials, add a message, and we'll deliver it anywhere in Pakistan — Cash on Delivery.</p>
        </div>
    </section>

    <form method="POST" action="{{ route('gift-box.store') }}" data-giftbox>
        @csrf
        @error('items') <p class="mb-4 rounded-2xl bg-coral-50 p-3 text-sm font-bold text-coral-700" role="alert">{{ $message }}</p> @enderror

        <h2 class="sr-only">Choose your items</h2>
        @php $gbSizes = '(min-width: 1024px) 23vw, (min-width: 640px) 31vw, 46vw'; @endphp
        @if ($products->isNotEmpty()) <x-preload-image :path="$products->first()->product_image" :sizes="$gbSizes" /> @endif
        <fieldset>
            <legend class="sr-only">Choose {{ $min }} to {{ $max }} items</legend>
            <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($products as $p)
                    @php $pay = \App\Support\Price::final($p); @endphp
                    <li>
                        <label class="group relative block cursor-pointer">
                            <input type="checkbox" name="items[]" value="{{ $p->id }}" data-price="{{ $pay }}" class="peer sr-only"
                                   @checked(in_array($p->id, old('items', [])))>
                            <span class="block overflow-hidden rounded-card border-2 border-line bg-white transition peer-checked:border-coral-600 peer-checked:shadow-lift peer-focus-visible:ring-4 peer-focus-visible:ring-brand-100">
                                {{-- The first card is the LCP on phones: preloaded above, never lazy. --}}
                                <x-picture :path="$p->product_image" :sizes="$gbSizes" :priority="$loop->first" width="300" height="300"
                                           class="aspect-square w-full bg-frame object-contain p-3 mix-blend-multiply" />
                                <span class="block p-3">
                                    <span class="line-clamp-2 min-h-[2.6em] text-[13px] font-bold leading-[1.3] text-ink">{{ $p->product_title }}</span>
                                    <span class="mt-1 block text-sm font-extrabold text-coral-600">{{ money($pay) }}</span>
                                </span>
                            </span>
                            <span class="absolute right-2 top-2 grid size-8 place-items-center rounded-full border-2 border-line bg-white text-transparent transition peer-checked:border-coral-600 peer-checked:bg-coral-600 peer-checked:text-white" aria-hidden="true">
                                <x-shop.icon name="check" class="size-4" />
                            </span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </fieldset>

        <label class="mt-6 block max-w-xl text-sm font-bold text-ink">Gift message <span class="font-normal text-muted">(optional)</span>
            <textarea name="message" maxlength="300" rows="2" placeholder="e.g. Mubarak ho! With love, Ayesha" class="mt-1.5 block w-full rounded-xl border border-line bg-white p-3 font-normal">{{ old('message') }}</textarea>
        </label>

        {{-- Live summary bar --}}
        <div class="fixed inset-x-0 bottom-[calc(4rem+env(safe-area-inset-bottom))] z-30 border-t border-line bg-white/95 px-4 py-3 shadow-lift backdrop-blur md:bottom-0">
            <div class="container-shop flex items-center gap-4 px-0">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-ink"><span data-gb-count>0</span> of {{ $max }} picked <span class="font-normal text-muted" data-gb-hint>— choose at least {{ $min }}</span></p>
                    <p class="text-lg font-extrabold text-coral-600" data-gb-total>{{ money(0) }}</p>
                </div>
                <button type="submit" class="btn btn-primary" data-gb-submit disabled>
                    <x-shop.icon name="gift" class="size-5" /> Add gift box
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
(function () {
    var form = document.querySelector('[data-giftbox]');
    var min = {{ $min }}, max = {{ $max }};
    var fmt = new Intl.NumberFormat('en-PK', { maximumFractionDigits: 0 });
    function sync() {
        var boxes = form.querySelectorAll('input[name="items[]"]');
        var on = Array.prototype.filter.call(boxes, function (b) { return b.checked; });
        var total = on.reduce(function (s, b) { return s + Number(b.dataset.price); }, 0);
        form.querySelector('[data-gb-count]').textContent = on.length;
        form.querySelector('[data-gb-total]').textContent = 'Rs. ' + fmt.format(total);
        form.querySelector('[data-gb-hint]').textContent = on.length < min ? '— choose at least ' + min : (on.length === max ? '— box is full' : '— ready!');
        form.querySelector('[data-gb-submit]').disabled = on.length < min;
        boxes.forEach(function (b) { if (!b.checked) b.disabled = on.length >= max; });
    }
    form.addEventListener('change', sync);
    sync();
})();
</script>
@endpush
