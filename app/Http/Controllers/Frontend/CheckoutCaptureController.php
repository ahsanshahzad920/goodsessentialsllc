<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCheckout;
use App\Support\PkPhone;
use Illuminate\Http\Request;

/**
 * POST /checkout/capture — called by the checkout page as soon as a valid
 * mobile number has been typed (before the order is placed), so the team can
 * follow up on WhatsApp if the shopper leaves. Only phone, name, city and
 * the cart lines are kept.
 */
class CheckoutCaptureController extends Controller
{
    public function __invoke(Request $request)
    {
        $phone = PkPhone::normalize($request->input('phone'));
        $cart = session('cart', []);

        if (! preg_match(PkPhone::PATTERN, $phone) || empty($cart)) {
            return response()->noContent();
        }

        $items = collect($cart)->map(fn ($l) => [
            'product_id' => $l['product_id'] ?? null,
            'title'      => $l['product_title'] ?? '',
            'color'      => $l['color_name'] ?? null,
            'quantity'   => (int) ($l['quantity'] ?? 1),
            'price'      => (float) ($l['product_price'] ?? 0),
        ])->values()->all();

        AbandonedCheckout::updateOrCreate(
            ['session_id' => $request->session()->getId()],
            [
                'phone'    => $phone,
                'name'     => mb_substr(strip_tags((string) $request->input('name')), 0, 120) ?: null,
                'city'     => mb_substr(strip_tags((string) $request->input('city')), 0, 120) ?: null,
                'items'    => $items,
                'subtotal' => collect($items)->sum(fn ($i) => $i['price'] * $i['quantity']),
                'order_id' => null,
            ]
        );

        return response()->noContent();
    }
}
