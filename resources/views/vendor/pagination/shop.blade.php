@if ($paginator->hasPages())
    @php
        $base = 'grid h-9 min-w-[2.25rem] place-items-center rounded-full border px-3 text-sm font-bold transition';
    @endphp

    <nav role="navigation" aria-label="Pagination" class="flex flex-wrap items-center justify-center gap-1.5">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="{{ $base }} border-line bg-white text-muted/50" aria-disabled="true">
                <x-shop.icon name="arrow-right" class="size-4 rotate-180" />
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page"
               class="{{ $base }} border-line bg-white text-ink hover:border-brand-500 hover:text-brand-500">
                <x-shop.icon name="arrow-right" class="size-4 rotate-180" />
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1 text-muted">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="{{ $base }} border-brand-500 bg-brand-500 text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" aria-label="Go to page {{ $page }}"
                           class="{{ $base }} border-line bg-white text-ink hover:border-brand-500 hover:text-brand-500">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page"
               class="{{ $base }} border-line bg-white text-ink hover:border-brand-500 hover:text-brand-500">
                <x-shop.icon name="arrow-right" class="size-4" />
            </a>
        @else
            <span class="{{ $base }} border-line bg-white text-muted/50" aria-disabled="true">
                <x-shop.icon name="arrow-right" class="size-4" />
            </span>
        @endif
    </nav>
@endif
