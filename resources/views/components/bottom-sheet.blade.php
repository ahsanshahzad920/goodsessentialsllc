{{--
    Accessible sheet built on native <dialog> (focus trap, Esc, inert page for free).
    Bottom sheet on phones; from md up it becomes a right-side drawer (side="right")
    or a centred modal (side="center").

    <x-bottom-sheet id="cart-sheet" title="Your cart"> … </x-bottom-sheet>
    <button data-sheet-open="cart-sheet">Open</button>       any element, anywhere
    <button data-sheet-close>Close</button>                  inside the sheet
    JS: window.sheet.open('cart-sheet') / window.sheet.close('cart-sheet')
    Events: 'sheet:open' / 'sheet:close' dispatched on the <dialog>.
--}}
@props(['id', 'title' => null, 'side' => 'right'])

@php
    $desktop = $side === 'center'
        ? 'md:inset-0 md:m-auto md:h-fit md:max-h-[85vh] md:w-full md:max-w-lg md:rounded-card'
        : 'md:inset-y-0 md:right-0 md:left-auto md:m-0 md:h-dvh md:max-h-dvh md:w-[26rem] md:max-w-[90vw] md:rounded-none md:rounded-l-card';
@endphp

<dialog id="{{ $id }}" data-sheet
        @if ($title) aria-labelledby="{{ $id }}-title" @endif
        {{ $attributes->class([
            'sheet fixed inset-x-0 bottom-0 top-auto m-0 flex max-h-[88dvh] w-full max-w-none flex-col overflow-hidden',
            'rounded-t-card bg-white p-0 text-ink shadow-lift backdrop:bg-ink/40 backdrop:backdrop-blur-[2px]',
            '[&:not([open])]:hidden',
            $desktop,
            'sheet-side-' . $side,
        ]) }}>
    <div class="mx-auto mt-2 h-1.5 w-10 shrink-0 rounded-full bg-line md:hidden" aria-hidden="true"></div>

    @if ($title || isset($header))
        <header class="flex shrink-0 items-center justify-between gap-3 border-b border-line px-5 py-3">
            @isset($header)
                {{ $header }}
            @else
                <h2 id="{{ $id }}-title" class="text-lg font-semibold">{{ $title }}</h2>
            @endisset
            <button type="button" data-sheet-close class="grid size-11 place-items-center rounded-full text-muted hover:bg-mist-100 hover:text-ink" aria-label="Close">
                <x-shop.icon name="x" class="size-5" />
            </button>
        </header>
    @endif

    <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain px-5 py-4">
        {{ $slot }}
    </div>

    @isset($footer)
        <footer class="shrink-0 border-t border-line bg-white px-5 py-4 pb-[max(1rem,env(safe-area-inset-bottom))]">
            {{ $footer }}
        </footer>
    @endisset
</dialog>

@once
        <style>
            /* Slide in/out using transform only. */
            .sheet[open] { animation: sheet-up .32s cubic-bezier(.2,.8,.2,1); }
            .sheet[open]::backdrop { animation: sheet-fade .32s ease; }
            @media (min-width: 768px) {
                .sheet-side-right[open] { animation-name: sheet-left; }
                .sheet-side-center[open] { animation-name: sheet-pop; }
            }
            @keyframes sheet-up { from { transform: translateY(100%); } }
            @keyframes sheet-left { from { transform: translateX(100%); } }
            @keyframes sheet-pop { from { transform: scale(.96); opacity: 0; } }
            @keyframes sheet-fade { from { opacity: 0; } }
            html:has(dialog[data-sheet][open]) { overflow: hidden; }
        </style>
        <script>
            window.sheet = {
                open: function (id) {
                    var d = document.getElementById(id);
                    if (!d || d.open) return;
                    d.showModal();
                    d.dispatchEvent(new CustomEvent('sheet:open'));
                },
                close: function (id) {
                    var d = typeof id === 'string' ? document.getElementById(id) : id;
                    if (!d || !d.open) return;
                    d.close();
                    d.dispatchEvent(new CustomEvent('sheet:close'));
                }
            };
            document.addEventListener('click', function (e) {
                var opener = e.target.closest('[data-sheet-open]');
                if (opener) { e.preventDefault(); window.sheet.open(opener.dataset.sheetOpen); return; }
                var closer = e.target.closest('[data-sheet-close]');
                if (closer) { window.sheet.close(closer.closest('dialog')); return; }
                // Click on the backdrop (the dialog element itself, outside its content box).
                if (e.target.matches('dialog[data-sheet]')) {
                    var r = e.target.getBoundingClientRect();
                    if (e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom) {
                        window.sheet.close(e.target);
                    }
                }
            });
        </script>
@endonce
