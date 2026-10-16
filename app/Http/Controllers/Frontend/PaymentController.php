<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Payments\PaymentManager;
use App\Payments\PaymentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /** GET /payment/start/{order_number} - send the customer to the gateway (also used to retry). */
    public function start(string $orderNumber)
    {
        $order = $this->findOrder($orderNumber);

        if (! $order->needsOnlinePayment()) {
            return redirect()->route('order.success', $order->order_number);
        }

        try {
            return redirect()->away(PaymentManager::gateway()->checkoutUrl($order));
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('order.success', $order->order_number)
                ->with('error', 'We could not start the payment. Please try again.');
        }
    }

    /** GET|POST /payment/callback/{order_number} - customer returns from the gateway. */
    public function callback(Request $request, string $orderNumber)
    {
        $order = $this->findOrder($orderNumber);

        if ($order->payment_status === 'paid') {
            return redirect()->route('order.success', $order->order_number);
        }

        try {
            $result = PaymentManager::gateway()->verifyReturn($order, $request);
        } catch (\Throwable $e) {
            report($e);
            $result = new PaymentResult(PaymentResult::PENDING);
        }

        $this->applyResult($order, $result);

        $flash = match ($result->status) {
            PaymentResult::PAID   => ['success', 'Payment received. Thank you!'],
            PaymentResult::FAILED => ['error', 'The payment was not completed. You can try again below.'],
            default               => ['success', "We're confirming your payment. This page will show Paid once it is confirmed."],
        };

        return redirect()->route('order.success', $order->order_number)->with($flash[0], $flash[1]);
    }

    /** POST /payment/webhook - gateway server-to-server notification (no session, no CSRF). */
    public function webhook(Request $request)
    {
        $class = PaymentManager::gatewayClass();

        if (! $class || ! $class::isConfigured()) {
            return response('Gateway not configured', 404);
        }

        try {
            $event = PaymentManager::gateway()->handleWebhook($request);
        } catch (\Throwable $e) {
            report($e);

            return response('Invalid notification', 400);
        }

        if ($event) {
            [$orderNumber, $result] = $event;

            $order = Order::where('order_number', $orderNumber)->first();

            if ($order) {
                $this->applyResult($order, $result);
            }
        }

        return response('OK');
    }

    /** GET /payment/fake/{order_number} - local test gateway page. */
    public function fake(string $orderNumber)
    {
        abort_unless(config('payments.gateway') === 'fake' && app()->environment('local', 'testing'), 404);

        $order = $this->findOrder($orderNumber);

        return view('payment.fake', compact('order'));
    }

    /** Idempotent: safe to call from both the return page and the webhook. */
    private function applyResult(Order $order, PaymentResult $result): void
    {
        if ($result->isPaid() && $order->payment_status !== 'paid') {
            $order->update([
                'payment_status'    => 'paid',
                'payment_reference' => $result->reference,
                'paid_at'           => now(),
            ]);
        }
    }

    private function findOrder(string $orderNumber): Order
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $isJustPlaced = (int) session('last_order_id') === (int) $order->id;
        $isOwner      = Auth::check() && (int) Auth::id() === (int) $order->user_id;

        abort_unless($isJustPlaced || $isOwner, 404);

        return $order;
    }
}
