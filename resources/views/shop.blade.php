@extends('layouts.guest')

@php
    use App\Models\Wishlist;
    use Illuminate\Support\Str;

    $wishlistIds = Auth::check()
        ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->all()
        : [];

    $sorts = [
        'latest'     => 'Latest',
        'price_asc'  => 'Price: Low to High',
        'price_desc' => 'Price: High to Low',
        'name'       => 'Name: A to Z',
    ];
    $currentSort    = request('sort', 'latest');
    $activeCategory = request()->category_name;
    $total          = $products->total();

    $input = 'w-full rounded-full border border-line bg-white px-4 py-2.5 text-xs text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none';
@endphp

@section('pageTitle', ($activeCategory ?: 'Shop') . ' | ' . config('app.name'))

@section('content')

{{-- Page header --}}
<section class="border-b border-line bg-white/70">
    <div class="container-shop flex flex-wrap items-end justify-between gap-4 py-6">
        <div>
            <p class="text-[11px] font-extrabold uppercase tracking-wider text-brand-500">Shop</p>
            <h1 class="mt-1 text-3xl font-bold">{{ $activeCategory ?: 'Shop' }}</h1>
            <p class="mt-0.5 text-xs text-muted">{{ number_format($total) }} {{ Str::plural('product', $total) }}</p>
        </div>

        {{-- Quick sort: keeps the current category + filters --}}
        <form method="GET" action="{{ url()->current() }}" class="flex items-end gap-2">
            @foreach (request()->except(['sort', 'page']) as $key => $value)
                @if (is_scalar($value))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <div class="w-36">
                <x-shop.select name="sort" id="sort-top" label="Sort">
                    @foreach ($sorts as $value => $label)
                        <option value="{{ $value }}" @selected($currentSort === $value)>{{ $label }}</option>
                    @endforeach
                </x-shop.select>
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-brand-500 px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-brand-500/30 transition hover:bg-brand-600">
                <x-shop.icon name="refresh" class="size-3.5" /> Update
            </button>
        </form>
    </div>
</section>

<div class="container-shop py-8">
    {{-- Mobile filter toggle --}}
    <button id="filters-toggle" type="button" aria-expanded="false" aria-controls="filters-panel"
            class="mb-4 inline-flex items-center gap-2 rounded-full border border-line bg-white px-4 py-2 text-xs font-bold lg:hidden">
        <x-shop.icon name="filter" class="size-4" /> Filters
    </button>

    <div class="grid gap-6 lg:grid-cols-[16.5rem_minmax(0,1fr)] lg:items-start">

        {{-- Filters --}}
        <aside id="filters-panel" aria-label="Product filters"
               class="hidden rounded-2xl border border-line bg-white p-4 shadow-sm lg:sticky lg:top-28 lg:block">
            <form id="filters-form" method="GET" action="{{ url()->current() }}" class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold">Filters</h2>
                    <a href="{{ route('shop') }}" class="text-[11px] font-semibold text-muted hover:text-brand-500">Reset</a>
                </div>

                <div>
                    <label for="f-search" class="mb-1.5 block text-xs font-bold">Search</label>
                    <input id="f-search" type="search" name="search" value="{{ request('search') }}"
                           placeholder="Search products..." autocomplete="off" class="{{ $input }}">
                </div>

                <x-shop.select name="category" id="f-category" label="Category">
                    <option value="" data-url="{{ route('shop') }}">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}" data-url="{{ route('shop', $category->name) }}"
                                @selected($activeCategory === $category->name)>
                            {{ $category->name }} ({{ $category->products_count ?? 0 }})
                        </option>
                    @endforeach
                </x-shop.select>

                {{-- Shown only if your controller passes $collections (id + name) --}}
                @if (isset($collections) && $collections->isNotEmpty())
                    <x-shop.select name="collection" label="Collection">
                        <option value="">All collections</option>
                        @foreach ($collections as $collection)
                            <option value="{{ $collection->id }}" @selected((string) request('collection') === (string) $collection->id)>{{ $collection->name }}</option>
                        @endforeach
                    </x-shop.select>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="f-min" class="mb-1.5 block text-xs font-bold">Min Price</label>
                        <input id="f-min" type="number" min="0" step="any" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="{{ $input }}">
                    </div>
                    <div>
                        <label for="f-max" class="mb-1.5 block text-xs font-bold">Max Price</label>
                        <input id="f-max" type="number" min="0" step="any" name="max_price" value="{{ request('max_price') }}" placeholder="9999" class="{{ $input }}">
                    </div>
                </div>

                <x-shop.select name="sort" id="f-sort" label="Sort">
                    @foreach ($sorts as $value => $label)
                        <option value="{{ $value }}" @selected($currentSort === $value)>{{ $label }}</option>
                    @endforeach
                </x-shop.select>

                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-full bg-brand-500 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600">
                    <x-shop.icon name="filter" class="size-4" /> Apply Filters
                </button>
            </form>
        </aside>

        {{-- Products --}}
        <div>
            @if ($products->count())
                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <x-shop.product-tile :product="$product" :wishlisted="in_array($product->id, $wishlistIds)" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links('vendor.pagination.shop') }}
                </div>
            @else
                <div class="rounded-2xl border border-line bg-white p-10 text-center">
                    <p class="text-lg font-semibold">No products found</p>
                    <p class="mt-1 text-sm text-muted">Try changing or clearing your filters.</p>
                    <a href="{{ route('shop') }}" class="mt-4 inline-flex rounded-full bg-brand-500 px-5 py-2.5 text-xs font-extrabold text-white hover:bg-brand-600">Reset filters</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function () {
        var form = document.getElementById('filters-form');
        var cat  = document.getElementById('f-category');

        // Category lives in the URL path (route('shop', $name)), so point the form at it on submit.
        if (form && cat) {
            form.addEventListener('submit', function () {
                var opt = cat.selectedOptions[0];
                if (opt && opt.dataset.url) form.action = opt.dataset.url;
            });
        }

        // Mobile: show/hide the filters panel
        var toggle = document.getElementById('filters-toggle');
        var panel  = document.getElementById('filters-panel');
        if (toggle && panel) {
            toggle.addEventListener('click', function () {
                panel.classList.toggle('hidden');
                toggle.setAttribute('aria-expanded', String(!panel.classList.contains('hidden')));
            });
        }
    })();
</script>
@endpush
