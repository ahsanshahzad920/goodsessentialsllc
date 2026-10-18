@extends('layouts.guest')

@php
    use App\Models\Wishlist;

    // One query for the whole page (the old view ran one query per product).
    $wishlistIds = Auth::check()
        ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->all()
        : [];

    $configuredCategories = collect(config('shop.categories', []))
        ->keyBy(fn ($category) => strtolower($category['name']));
    $categories = \App\Models\Category::orderBy('name')
            ->get()
            ->map(function ($category) use ($configuredCategories) {
                $settings = $configuredCategories->get(strtolower($category->name), []);

                return [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'label' => $settings['label'] ?? $category->name,
                    'icon' => $settings['icon'] ?? 'tag',
                    'tone' => $settings['tone'] ?? 'lavender',
                ];
            });

    // Promo cards reference categories by name in config/shop.php; resolve
    // to a real slug when one exists, otherwise fall back to the full shop page.
    $promoHref = function (?string $categoryName) use ($categories) {
        if (! $categoryName) {
            return route('shop');
        }
        $match = $categories->firstWhere(fn ($c) => strcasecmp($c['name'], $categoryName) === 0);
        return $match ? route('shop.category', $match['slug']) : route('shop');
    };
@endphp

@section('content')
<div class="container-shop space-y-10 py-8">

    {{-- Hero banner --}}
    <x-shop.hero-banner />

    {{-- Category circles (from config/shop.php) --}}
    <section aria-label="Shop by category" class="grid grid-cols-[repeat(auto-fit,minmax(5.5rem,1fr))] gap-y-6">
        @foreach ($categories as $c)
            <x-shop.category-circle :label="$c['label'] ?? $c['name']" :icon="$c['icon']" :tone="$c['tone']" :href="route('shop.category', $c['slug'])" />
        @endforeach
    </section>

    {{-- Promo cards (from config/shop.php) --}}
    <section aria-label="Featured collections" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach (config('shop.promos', []) as $p)
            <x-shop.promo-card :title="$p['title']" :text="$p['text']" :tone="$p['tone']"
                               :image="asset($p['image'])"
                               :href="$promoHref($p['category'] ?? null)" />
        @endforeach
    </section>

    {{-- Popular picks: $babyProducts --}}
    <section aria-label="Popular picks" class="space-y-4">
        <x-shop.section-heading title="Popular Picks" :heart="true" link-text="View All Products" :href="route('shop')" />
        @if ($babyProducts->isNotEmpty())
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($babyProducts->take(6) as $product)
                    <x-shop.product-card :product="$product" :wishlisted="in_array($product->id, $wishlistIds)" />
                @endforeach
            </div>
        @else
            <p class="rounded-2xl bg-white p-6 text-center text-sm text-muted">No products found yet.</p>
        @endif
    </section>

    {{-- Deals of the week: $latestProducts --}}
    @if ($latestProducts->isNotEmpty())
        <section aria-label="Deals of the week" class="space-y-4">
            <x-shop.section-heading title="Deals of the Week" link-text="Shop All Deals" :href="route('shop')" />
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($latestProducts->take(6) as $product)
                    <x-shop.product-card :product="$product" :wishlisted="in_array($product->id, $wishlistIds)" />
                @endforeach
            </div>
        </section>
    @endif

    <x-shop.trust-bar />
</div>
@endsection
