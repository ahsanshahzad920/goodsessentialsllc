<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Payments\PaymentManager;
use App\Services\SeoService;
use App\Support\Area;
use App\Support\Price;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /** GET /checkout */
    public function show()
    {
        SeoService::set('Checkout | ' . config('app.name'), 'Secure checkout.', index: false);

        [$lines, $subtotal, $removed] = $this->cartLines(session('cart', []));

        $defaultMethod = array_key_first(PaymentManager::methods());
        $shipping = $this->shippingFor($lines, $defaultMethod, $subtotal);
        $methods  = PaymentManager::methods();

        if ($removed) {
            session()->flash('error', 'Some items in your cart are no longer available and were removed.');
        }

        return view('checkout', [
            'lines'          => $lines,
            'subtotal'       => $subtotal,
            'shipping'       => $shipping,
            'total'          => round($subtotal + $shipping, 2),
            'paymentMethods' => $methods,
            'defaultMethod' => $defaultMethod,
        ]);
    }

    /** POST /checkout  (guests and logged-in users) */
    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        [$lines, $subtotal, $removed] = $this->cartLines($cart);

        if (empty($lines) || $removed) {
            return redirect()->route('checkout')
                ->with('error', 'Some items in your cart are no longer available and were removed. Please review your order.');
        }

        $wantsAccount = ! Auth::check() && $request->boolean('create_account');
        $methods      = PaymentManager::methods();

        // Phase 6: one "Full name" box instead of first/last (split here), and a
        // Pakistani mobile number normalised to 03XXXXXXXXX before validation.
        if ($request->filled('full_name')) {
            $parts = preg_split('/\s+/', trim((string) $request->input('full_name')), 2);
            $request->merge(['first_name' => $parts[0] ?? '', 'last_name' => $parts[1] ?? '']);
        }
        $request->merge(['phone' => \App\Support\PkPhone::normalize($request->input('phone'))]);

        $rules = [
            'first_name'     => ['required', 'string', 'max:100'],
            'last_name'      => ['nullable', 'string', 'max:100'],
            'phone'          => ['required', 'regex:' . \App\Support\PkPhone::PATTERN],
            // Optional: COD shoppers often have no email. Needed only for an account.
            'email'          => [$wantsAccount ? 'required' : 'nullable', 'email', 'max:255'],
            'address'        => ['required', 'string', 'min:8', 'max:255'],
            'city'           => ['required', 'string', 'max:120'],
            'zip_code'       => ['nullable', 'string', 'max:20'],
            'notes'          => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', Rule::in(array_keys($methods))],
            'terms'          => ['accepted'],
        ];

        if ($wantsAccount) {
            $rules['email'][]  = Rule::unique('users', 'email');
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $data = $request->validate($rules, [
            'first_name.required'     => 'Please enter your name.',
            'phone.required'          => 'Please enter your mobile number.',
            'phone.regex'             => 'Please enter a Pakistani mobile number like 0300-1234567.',
            'address.min'             => 'Please enter your full address (house, street, area).',
            'email.unique'            => 'An account with this email already exists. Please log in, or untick "Create an account" to check out as a guest.',
            'terms.accepted'          => 'Please accept the Terms & Conditions to place your order.',
            'payment_method.required' => 'Please choose a payment method.',
            'payment_method.in'       => 'That payment method is not available right now.',
        ]);

        // orders.email / last_name / zip_code are NOT NULL columns: store '' when empty.
        $data['last_name'] = $data['last_name'] ?? '';
        $data['email']     = $data['email'] ?? '';
        $data['zip_code']  = $data['zip_code'] ?? '';

        $shipping = $this->shippingFor($lines, $data['payment_method'], $subtotal);
        $total    = round($subtotal + $shipping, 2);
        $user     = Auth::user(); // null for guests
        $ip = $request->ip(); //  user ip
        $order = DB::transaction(function () use ($data, $lines, $subtotal, $shipping, $total, $wantsAccount, $ip, &$user) {
            if ($wantsAccount) {
                $attributes = [
                    'name'     => trim($data['first_name'] . ' ' . $data['last_name']),
                    'email'    => $data['email'],
                    'phone'    => $data['phone'],
                    'password' => Hash::make($data['password']),
                ];

                // Only fill these if your users table has the columns
                if (Schema::hasColumn('users', 'first_name')) {
                    $attributes['first_name'] = $data['first_name'];
                    $attributes['last_name']  = $data['last_name'];
                }

                $user = new User();
                $user->forceFill($attributes)->save();

                Area::heal($user); // gives the new account the `customer` role
            }

            // Tracked products: lock, verify, decrement (rolls back with the order on failure).
            \App\Support\Stock::reserve($lines);

            $order = Order::create([
                'order_number'   => Order::generateNumber(),
                'user_id'        => $user?->id,
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'email'          => $data['email'],
                'phone'          => $data['phone'],
                'address'        => $data['address'],
                'city'           => $data['city'],
                'zip_code'       => $data['zip_code'],
                'notes'          => $data['notes'] ?? null,
                'subtotal'       => $subtotal,
                'shipping'       => $shipping,
                'total'          => $total,
                'payment_method' => $data['payment_method'],
                'status'         => 'pending',
                'payment_status' => 'unpaid', // COD: becomes "paid" when the cash is collected; card: when the gateway confirms
                'ip_address'     => $ip,
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'product_id'    => $line['product']->id,
                    'product_title' => $line['product']->product_title,
                    'product_image' => $line['product']->product_image,
                    'price'         => $line['price'],
                    'quantity'      => $line['quantity'],
                    'total'         => $line['total'],
                    'color_name' => $line['color_name'] ?? null,
                    'color_hex'  => $line['color_hex'] ?? null,
                ]);
            }

            return $order;
        });

        // Sign the new account in (after the transaction succeeded)
        if ($wantsAccount && $user) {
            try {
                event(new Registered($user)); // sends the verification email
            } catch (\Throwable $e) {
                report($e); // never lose a placed order because of a mail problem
            }

            Auth::login($user);
            $request->session()->regenerate();
        }

        session()->forget(['cart', 'checkout_note']);
        session()->put('last_order_id', $order->id);
        session()->put('account_created', $wantsAccount);
        // Consumed exactly once on the order-success page (session()->pull()),
        // so a refresh of that page never re-fires the TikTok PlaceAnOrder event.
        session()->put('ttq_pending_place_order_' . $order->id, true);

        // and send a hidden copy to the admin.
        try {
            // Email is optional at checkout: without one, only the admin copy goes out.
            $mail = $order->email !== ''
                ? Mail::to($order->email)->bcc(config('shop.admin_emails'))
                : Mail::to(config('shop.admin_emails'));
            $mail->queue(new OrderConfirmationMail($order));
        } catch (\Throwable $e) {
            report($e);
        }

        // Abandoned-checkout follow-up list: this phone/session has now ordered.
        \App\Models\AbandonedCheckout::markConverted($order, $request->session()->getId());

        // Integration point for WhatsApp / SMS confirmations (see App\Listeners\SendOrderConfirmationMessage).
        rescue(fn () => event(new \App\Events\OrderPlaced($order)), report: true);

        // Card orders go to the gateway; cash on delivery is complete.
        if ($order->payment_method === 'card') {
            return redirect()->route(
                'payment.start',
                $order->order_number
            );
        }

        return redirect()->route('order.success',$order->order_number);
    }

    /** GET /order/success/{order_number} */
    public function success(string $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        // Guests may only see the order they just placed; owners may always see theirs.
        $isJustPlaced = (int) session('last_order_id') === (int) $order->id;
        $isOwner      = Auth::check() && (int) Auth::id() === (int) $order->user_id;

        abort_unless($isJustPlaced || $isOwner, 404);

        return view('order-success', [
            'order'          => $order,
            'accountCreated' => (bool) session()->pull('account_created', false),
            // One-shot flags for the TikTok events on this page — see store()
            // and PaymentController::applyResult() for where each is set.
            'firePlaceAnOrder'    => (bool) session()->pull('ttq_pending_place_order_' . $order->id, false),
            'fireCompletePayment' => (bool) session()->pull('ttq_pending_complete_payment_' . $order->id, false),
            // "Complete the set": other in-stock products from the same categories.
            'completeSet' => Product::withCardData()->forBabySections()
                ->whereIn('category_id', Product::whereIn('id', $order->items->pluck('product_id')->filter())->pluck('category_id'))
                ->whereNotIn('id', $order->items->pluck('product_id')->filter())
                ->where(fn ($q) => $q->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0))
                ->take(4)->get(),
        ]);
    }

    /**
     * Re-price every cart line from the database (never trust prices stored in the session).
     * Returns [lines, subtotal, anyRemoved].
     */
    private function cartLines(array $cart): array
    {
        $ids = collect($cart)->map(fn ($item, $key) => (int) ($item['product_id'] ?? $key))->all();
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');

        $lines = [];
        $removed = false;

        foreach ($cart as $key => $item) {
            $product = $products->get((int) ($item['product_id'] ?? $key));

            if (! $product) {
                $removed = true;
                unset($cart[$key]);
                continue;
            }

            $quantity = max(1, min(99, (int) ($item['quantity'] ?? 1)));
            $price    = Price::final($product);

            $lines[] = [
                'product'  => $product,
                'quantity' => $quantity,
                'price'    => $price,
                'total'    => round($price * $quantity, 2),
                'color_name' => $item['color_name'] ?? null,
                'color_hex'  => $item['color_hex'] ?? null,
            ];
        }

        if ($removed) {
            session()->put('cart', $cart);
        }

        return [$lines, round(array_sum(array_column($lines, 'total')), 2), $removed];
    }

    private function shippingFor(array $lines, ?string $paymentMethod, float $subtotal): float
    {
        if (empty($lines)) {
            return 0.0;
        }

        // Base charge per payment method, or 0 once the admin free-delivery
        // threshold (Settings) is reached. See App\Support\Shipping.
        return \App\Support\Shipping::for($subtotal, $paymentMethod);
    }
}
