<?php

namespace App\Payments\Contracts;

use App\Models\Order;
use App\Payments\PaymentResult;
use Illuminate\Http\Request;

/**
 * Every card gateway (Stripe, Safepay, PayFast, ...) is one class implementing this.
 */
interface PaymentGateway
{
    /** True when the gateway has everything it needs (API keys etc.). Used to show/hide "Card Payment". */
    public static function isConfigured(): bool;

    /** URL where the customer pays for $order (build it from $order->total, never from request data). */
    public function checkoutUrl(Order $order): string;

    /**
     * Customer came back from the gateway. Verify with the gateway SERVER-SIDE;
     * never trust the query string / form values alone.
     */
    public function verifyReturn(Order $order, Request $request): PaymentResult;

    /**
     * Gateway server-to-server notification. Verify the signature, then return
     * [orderNumber, PaymentResult] or null when the event is not relevant.
     * Throw an exception when the signature is invalid.
     */
    public function handleWebhook(Request $request): ?array;
}
