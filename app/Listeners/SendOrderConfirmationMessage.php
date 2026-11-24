<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Support\PkPhone;
use Illuminate\Support\Facades\Log;

/**
 * Integration point for a WhatsApp Business API / SMS order confirmation.
 * No provider is configured yet, so this only logs what it would send.
 * To go live: set ORDER_MESSAGE_DRIVER and implement send() for that provider.
 */
class SendOrderConfirmationMessage
{
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;
        $to = PkPhone::whatsapp($order->phone);
        $text = "Assalam o Alaikum {$order->first_name}! Your Goods Essentials order {$order->order_number} "
            . '(' . money($order->total) . ') is confirmed. We will call/WhatsApp you before dispatch. Thank you!';

        $driver = config('services.order_messages.driver');
        if (! $driver) {
            Log::info('Order confirmation message (no provider configured)', ['to' => $to, 'order' => $order->order_number]);
            return;
        }

        $this->send($driver, $to, $text);
    }

    private function send(string $driver, string $to, string $text): void
    {
        // e.g. Meta WhatsApp Cloud API template message, or an SMS gateway.
        Log::warning("Order message driver [{$driver}] is set but not implemented yet.", ['to' => $to]);
    }
}
