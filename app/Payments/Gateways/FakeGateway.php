<?php

namespace App\Payments\Gateways;

use App\Models\Order;
use App\Payments\Contracts\PaymentGateway;
use App\Payments\PaymentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * LOCAL TESTING ONLY. Shows a page with "success" / "fail" buttons.
 * It refuses to run outside the local/testing environments.
 */
class FakeGateway implements PaymentGateway
{
    public static function isConfigured(): bool
    {
        return app()->environment('local', 'testing');
    }

    public function checkoutUrl(Order $order): string
    {
        return route('payment.fake', $order->order_number);
    }

    public function verifyReturn(Order $order, Request $request): PaymentResult
    {
        abort_unless(static::isConfigured(), 404);

        return $request->input('outcome') === 'success'
            ? new PaymentResult(PaymentResult::PAID, 'FAKE-' . Str::upper(Str::random(10)))
            : new PaymentResult(PaymentResult::FAILED);
    }

    public function handleWebhook(Request $request): ?array
    {
        return null; // the fake gateway has no webhooks
    }
}
