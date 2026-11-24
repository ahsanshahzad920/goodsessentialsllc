<div id="whatsapp-chat-widget" class="font-['Nunito']">

    <!-- Floating WhatsApp Button -->
    <button
        id="wa-toggle"
        type="button"
        aria-label="Open customer support chat"
        aria-expanded="false"
        class="fixed {{ request()->routeIs('gift-box') ? 'bottom-[calc(10rem+env(safe-area-inset-bottom))] md:bottom-24' : 'bottom-[calc(5rem+env(safe-area-inset-bottom))] md:bottom-6' }} right-4 z-[9998] flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-3xl text-white shadow-lg transition duration-200 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-mint-100 sm:right-6 sm:h-[60px] sm:w-[60px]"
    >
        <x-shop.icon name="whatsapp" class="size-8 shrink-0" />
    </button>

    {{-- Pre-chat nudge: once per session, 15s after arrival, never while the button is hidden --}}
    <div id="wa-nudge" hidden
         class="fixed bottom-[calc(9.25rem+env(safe-area-inset-bottom))] right-4 z-[9998] max-w-[15rem] translate-y-2 rounded-2xl rounded-br-sm bg-white py-3 pl-4 pr-9 text-sm font-bold text-ink opacity-0 shadow-lift transition-[transform,opacity] duration-300 ease-soft md:bottom-[5.75rem] sm:right-6">
        <button type="button" data-wa-nudge-open class="text-left">Need help choosing? <span class="text-[#0e7a3f]">Chat with us</span> 👋</button>
        <button type="button" data-wa-nudge-close class="absolute right-1 top-1 grid size-8 place-items-center rounded-full text-muted hover:bg-mist-100" aria-label="Dismiss">&times;</button>
    </div>
    <script>
        (function () {
            var n = document.getElementById('wa-nudge'), btn = document.getElementById('wa-toggle');
            try { if (sessionStorage.getItem('wa-nudged')) return; } catch (e) {}
            if (/\/(checkout|order\/success)/.test(location.pathname)) return;
            setTimeout(function () {
                // Skip if the WhatsApp button is currently hidden (e.g. over the homepage hero).
                if (!btn || getComputedStyle(btn).opacity === '0' || btn.getAttribute('aria-expanded') === 'true') return;
                try { sessionStorage.setItem('wa-nudged', '1'); } catch (e) {}
                n.hidden = false;
                requestAnimationFrame(function () { n.classList.remove('translate-y-2', 'opacity-0'); });
                setTimeout(close, 12000);
            }, 15000);
            function close() { n.classList.add('translate-y-2', 'opacity-0'); setTimeout(function () { n.hidden = true; }, 300); }
            n.querySelector('[data-wa-nudge-close]').addEventListener('click', close);
            n.querySelector('[data-wa-nudge-open]').addEventListener('click', function () { close(); btn.click(); });
        })();
    </script>

    <!-- Chat Popup -->
    <div
        id="wa-panel"
        hidden
        class="fixed bottom-[calc(9.5rem+env(safe-area-inset-bottom))] left-3 right-3 z-[9999] flex h-[514px] max-h-[calc(100dvh-11rem)] flex-col overflow-hidden rounded-2xl bg-white shadow-2xl md:bottom-[100px] sm:left-auto sm:right-6 sm:h-[514px] sm:w-[360px] sm:max-w-[calc(100vw-48px)] sm:max-h-[calc(100dvh-120px)]"
    >

        <!-- Header -->
        <div class="flex shrink-0 items-center justify-between gap-3 bg-[#159447] px-4 py-4 text-white sm:p-[18px]">
            <div>
                <strong class="block text-base font-bold">Goods Essentials</strong>
                <small class="mt-1 block text-xs opacity-90">
                    Customer Support
                </small>
            </div>

            <button
                id="wa-close"
                type="button"
                aria-label="Close chat"
                class="rounded-lg px-2 py-1 text-3xl leading-none transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white"
            >
                &times;
            </button>
        </div>

        <!-- Scrollable Body -->
        <div class="min-h-0 flex-1 space-y-4 overflow-y-auto overscroll-contain p-4 sm:p-[18px]">

            <!-- Welcome Message -->
            <div class="rounded-xl bg-[#f1f5f0] p-3 text-sm leading-6 text-ink">
                Hi there! 👋 Welcome to Goods Essentials.
                How can we help you today?
            </div>

            <div>
                <p class="mb-3 text-xs font-semibold text-muted">
                    Choose an option:
                </p>

                <!-- Options -->
                <div class="space-y-2">

                    <button
                        type="button"
                        class="wa-option w-full rounded-xl border border-line bg-white p-3 text-left text-sm transition hover:border-[#25D366] hover:bg-mint-100 focus:outline-none focus:ring-2 focus:ring-mint-100"
                        data-message="Hi! I need help finding a product."
                    >
                        🧸 Product Inquiry
                    </button>

                    <button
                        type="button"
                        class="wa-option w-full rounded-xl border border-line bg-white p-3 text-left text-sm transition hover:border-[#25D366] hover:bg-mint-100 focus:outline-none focus:ring-2 focus:ring-mint-100"
                        data-message="Hi! I need help with my order."
                    >
                        📦 Order Support
                    </button>

                    <button
                        type="button"
                        class="wa-option w-full rounded-xl border border-line bg-white p-3 text-left text-sm transition hover:border-[#25D366] hover:bg-mint-100 focus:outline-none focus:ring-2 focus:ring-mint-100"
                        data-message="Hi! I have a question about shipping."
                    >
                        🚚 Shipping Information
                    </button>

                    <button
                        type="button"
                        class="wa-option w-full rounded-xl border border-line bg-white p-3 text-left text-sm transition hover:border-[#25D366] hover:bg-mint-100 focus:outline-none focus:ring-2 focus:ring-mint-100"
                        data-message="Hi! I have a question about returns or refunds."
                    >
                        ↩️ Returns &amp; Refunds
                    </button>

                </div>
            </div>

            <!-- WhatsApp Link -->
            <a
                id="wa-start-chat"
                href="#"
                target="_blank"
                rel="noopener noreferrer"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#0e7a3f] px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-[#0b6533] focus:outline-none focus:ring-4 focus:ring-mint-100"
            >
                <x-shop.icon name="whatsapp" class="size-5 shrink-0" />
                Chat with us on WhatsApp
            </a>

            <p class="pb-1 text-center text-xs leading-5 text-muted">
                WhatsApp will open so you can send your message.
            </p>

        </div>
    </div>
</div>

<script>
    (() => {
        const widget = document.getElementById('whatsapp-chat-widget');

        if (!widget || widget.dataset.initialized === 'true') {
            return;
        }

        widget.dataset.initialized = 'true';

        const toggle = widget.querySelector('#wa-toggle');
        const panel = widget.querySelector('#wa-panel');
        const close = widget.querySelector('#wa-close');
        const startChat = widget.querySelector('#wa-start-chat');
        const options = widget.querySelectorAll('.wa-option');

        // WhatsApp number in international format without + or spaces.
        const phone = {!! json_encode(preg_replace('/[^0-9]/', '', config('shop.contact.whatsapp'))) !!};

        let message = 'Hello! I need help with Goods Essentials.';

        function updateLink() {
            startChat.href =
                'https://wa.me/' + phone +
                '?text=' + encodeURIComponent(message);
        }

        function openPanel() {
            panel.hidden = false;
            toggle.setAttribute('aria-expanded', 'true');
        }

        function closePanel() {
            panel.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        }

        toggle.addEventListener('click', () => {
            if (panel.hidden) {
                openPanel();
            } else {
                closePanel();
            }
        });

        close.addEventListener('click', closePanel);

        options.forEach(button => {
            button.addEventListener('click', () => {
                message = button.dataset.message;
                updateLink();

                if (typeof ttqTrack === 'function') {
                    ttqTrack('Contact', { content_name: 'whatsapp_' + (button.dataset.message || 'chat') });
                }

                // Open WhatsApp with the selected message.
                window.open(
                    startChat.href,
                    '_blank',
                    'noopener,noreferrer'
                );
            });
        });

        startChat.addEventListener('click', () => {
            if (typeof ttqTrack === 'function') {
                ttqTrack('Contact', { content_name: 'whatsapp_chat' });
            }
        });

        updateLink();
    })();
</script>