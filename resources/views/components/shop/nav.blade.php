@php
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


@endphp

<nav class="border-b border-line bg-white" aria-label="Categories">
    <ul class="container-shop flex items-center gap-6 overflow-x-auto py-2.5 text-sm font-bold [scrollbar-width:none]">
        @php $home = request()->routeIs('index'); @endphp
        <li class="shrink-0">
            <a href="{{ route('index') }}" @if($home) aria-current="page" @endif
               class="flex items-center gap-1.5 border-b-2 py-1.5 {{ $home ? 'border-brand-500 text-brand-500' : 'border-transparent text-ink hover:text-brand-500' }}">
                <x-shop.icon name="home" class="size-4" /> Home
            </a>
        </li>

        @foreach ($categories as $c)
            @php
                $href = route('shop.category', $c['slug']);
                $active = request()->url() === $href;
            @endphp
            <li class="shrink-0">
                <a href="{{ $href }}" @if($active) aria-current="page" @endif
                   class="flex items-center gap-1.5 border-b-2 py-1.5 {{ $active ? 'border-brand-500 text-brand-500' : 'border-transparent text-ink hover:text-brand-500' }}">
                    <x-shop.icon :name="$c['icon']" class="size-4" /> {{ $c['label'] ?? $c['name'] }}
                </a>
            </li>
        @endforeach

        <li class="ml-auto shrink-0">
            {{-- Point this at your deals page / filter when you have one --}}
            <a href="{{ route('shop') }}" class="flex items-center gap-1.5 rounded-full bg-sun-100 px-4 py-2 text-sm font-bold text-sun-600 hover:brightness-95">
                <x-shop.icon name="star" class="size-4 fill-blush-600 text-blush-600" /> Deals
            </a>
        </li>
    </ul>
</nav>
