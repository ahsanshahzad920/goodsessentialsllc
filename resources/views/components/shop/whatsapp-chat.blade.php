
<div id="whatsapp-chat-widget">
    <!-- Floating button -->
    <button id="wa-toggle" type="button"
        aria-label="Open customer support chat"
        aria-expanded="false">
        <i class="fa-brands fa-whatsapp"></i>
    </button>

    <!-- Chat popup -->
    <div id="wa-panel" class="wa-panel" hidden>
        <div class="wa-header">
            <div>
                <strong>Good Essentials</strong>
                <small>Customer Support</small>
            </div>
            <button id="wa-close" type="button"
                aria-label="Close chat">&times;</button>
        </div>

        <div class="wa-body">
            <div class="wa-message">
                Hi there! 👋 Welcome to Good Essentials.
                How can we help you today?
            </div>

            <p class="wa-label">Choose an option:</p>

            <button class="wa-option"
                data-message="Hi! I need help finding a product.">
                🧸 Product Inquiry
            </button>

            <button class="wa-option"
                data-message="Hi! I need help with my order.">
                📦 Order Support
            </button>

            <button class="wa-option"
                data-message="Hi! I have a question about shipping.">
                🚚 Shipping Information
            </button>

            <button class="wa-option"
                data-message="Hi! I have a question about returns or refunds.">
                ↩️ Returns & Refunds
            </button>

            <a id="wa-start-chat"
                href="#"
                target="_blank"
                rel="noopener noreferrer">
                <i class="fa-brands fa-whatsapp"></i>
                Chat with us on WhatsApp
            </a>

            <small class="wa-note">
                WhatsApp will open so you can send your message.
            </small>
        </div>
    </div>
</div>

<style>
    #whatsapp-chat-widget {
        font-family: 'Nunito', sans-serif;
    }

    #wa-toggle {
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 60px;
        height: 60px;
        border: 0;
        border-radius: 50%;
        background: #25D366;
        color: white;
        font-size: 34px;
        cursor: pointer;
        box-shadow: 0 4px 14px #0003;
        z-index: 9998;
    }

    .wa-panel {
        position: fixed;
        right: 24px;
        height: 514px;
        bottom: 55px;
        width: 340px;
        max-width: calc(100vw - 32px);
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 35px #0002;
        z-index: 9999;
    }

    .wa-panel[hidden] {
        display: none !important;
    }

    .wa-header {
        background: #159447;
        color: white;
        padding: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .wa-header strong,
    .wa-header small {
        display: block;
    }

    .wa-header small {
        margin-top: 4px;
        opacity: .9;
    }

    #wa-close {
        background: transparent;
        border: 0;
        color: white;
        font-size: 28px;
        cursor: pointer;
    }

    .wa-body {
        padding: 18px;
    }

    .wa-message {
        background: #f1f5f0;
        padding: 13px;
        border-radius: 12px;
        line-height: 1.6;
        font-size: 14px;
    }

    .wa-label {
        font-size: 13px;
        color: #666;
        margin: 16px 0 10px;
    }

    .wa-option {
        display: block;
        width: 100%;
        padding: 11px;
        margin-bottom: 8px;
        text-align: left;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        cursor: pointer;
        font: inherit;
        font-size: 14px;
    }

    .wa-option:hover {
        background: #f0fff5;
        border-color: #25D366;
    }

    #wa-start-chat {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px;
        margin-top: 14px;
        background: #25D366;
        color: white;
        text-decoration: none;
        font-weight: 700;
        border-radius: 9px;
    }

    .wa-note {
        display: block;
        text-align: center;
        color: #777;
        font-size: 11px;
        margin-top: 10px;
    }

    @media (max-width: 480px) {
        #wa-toggle {
            right: 16px;
            bottom: 16px;
        }

        .wa-panel {
            right: 16px;
            bottom: 88px;
            width: 360px;
        }
    }
</style>

<script>
    (() => {
        const toggle = document.getElementById('wa-toggle');
        const panel = document.getElementById('wa-panel');
        const close = document.getElementById('wa-close');
        const startChat = document.getElementById('wa-start-chat');

        // Replace with your actual WhatsApp number.
        const phone = '923060974495';

        let message = 'Hello! I need help with Good Essentials.';

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

        document.querySelectorAll('#wa-panel .wa-option')
            .forEach(button => {
                button.addEventListener('click', () => {
                    message = button.dataset.message;
                    updateLink();
                    startChat.click();
                });
            });

        updateLink();
    })();
</script>
