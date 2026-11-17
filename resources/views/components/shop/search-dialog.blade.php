{{--
    Full-screen search for phones (header search icon + bottom-nav "Search").
    Shares the instant-results script with the desktop box (x-shop.search-script).
--}}
<dialog id="search-dialog" data-sheet aria-label="Search products"
        class="sheet sheet-side-center fixed inset-0 m-0 h-dvh max-h-none w-full max-w-none bg-cream p-0 text-ink backdrop:bg-ink/40 [&:not([open])]:hidden md:hidden">
    <div class="flex h-full flex-col" data-search>
        <form action="{{ route('shop') }}" method="GET" role="search"
              class="flex shrink-0 items-center gap-2 border-b border-line bg-white px-3 py-2.5">
            <button type="button" data-sheet-close class="grid size-11 shrink-0 place-items-center rounded-full text-ink hover:bg-mist-100" aria-label="Close search">
                <x-shop.icon name="chevron-left" class="size-6" />
            </button>
            <label for="mobile-search" class="sr-only">Search products</label>
            <input id="mobile-search" name="q" type="search" placeholder="Search swaddles, caps, toys…" enterkeyhint="search"
                   autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="mobile-search-results"
                   data-search-input
                   class="min-h-11 w-full rounded-full border border-line bg-cream px-4 text-base text-ink placeholder:text-muted focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-100">
        </form>
        <div id="mobile-search-results" data-search-panel class="min-h-0 flex-1 overflow-y-auto p-3"></div>
    </div>
</dialog>
