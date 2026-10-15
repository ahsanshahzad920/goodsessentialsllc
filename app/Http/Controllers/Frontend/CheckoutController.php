<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Support\Area;
use App\Support\Price;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CheckoutController extends Controller
{
    /** GET /checkout */
    public function show()
    {
        [$lines, $subtotal, $removed] = $this->cartLines(session('cart', []));

        $shipping = $this->shippingFor($lines);

        if ($removed) {
            session()->flash('error', 'Some items in your cart are no longer available and were removed.');
        }

        return view('checkout', [
            'lines'    => $lines,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total'    => round($subtotal + $shipping, 2),
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

        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'phone'      => ['required', 'string', 'max:30'],
            'email'      => ['required', 'email', 'max:255'],
            'address'    => ['required', 'string', 'max:255'],
            'city'       => ['required', 'string', 'max:120'],
            'zip_code'   => ['required', 'string', 'max:20'],
            'notes'      => ['nullable', 'string', 'max:1000'],
            'terms'      => ['accepted'],
        ];

        if ($wantsAccount) {
            $rules['email'][]  = Rule::unique('users', 'email');
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $data = $request->validate($rules, [
            'email.unique'   => 'An account with this email already exists. Please log in, or untick "Create an account" to check out as a guest.',
            'terms.accepted' => 'Please accept the Terms & Conditions to place your order.',
        ]);

        $shipping = $this->shippingFor($lines);
        $total    = round($subtotal + $shipping, 2);
        $user     = Auth::user(); // null for guests

        $order = DB::transaction(function () use ($data, $lines, $subtotal, $shipping, $total, $wantsAccount, &$user) {
            if ($wantsAccount) {
                $attributes = [
                    'name'     => trim($data['first_name'] . ' ' . $data['last_name']),
                    'email'    => $data['email'],
                    'phone'    => $data['phone'],
                    'password' => Hash::make($data['password']),
                ];

                if (Schema::hasColumn('users', 'first_name')) {
                    $attributes['first_name'] = $data['first_name'];
                    $attributes['last_name']  = $data['last_name'];
                }

                $user = new User();
                $user->forceFill($attributes)->save();

                Area::heal($user);
            }

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
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'product_id'    => $line['product']->id,
                    'product_title' => $line['product']->product_title,
                    'product_image' => $line['product']->product_image,
                    'price'         => $line['price'],
                    'quantity'      => $line['quantity'],
                    'total'         => $line['total'],
                ]);
            }

            return $order;
        });

        // Sign the new account in (after the transaction succeeded)
        if ($wantsAccount && $user) {
            try {
                event(new Registered($user)); // sends the verification email if your User implements MustVerifyEmail
            } catch (\Throwable $e) {
                report($e); // never lose a placed order because of a mail problem
            }

            Auth::login($user);
            $request->session()->regenerate();
        }

        session()->forget('cart');
        session()->put('last_order_id', $order->id);

        // >>> Payment gateway hook: start payment for $order here if/when you add one. <<<

        return redirect()
            ->route('order.success', $order->order_number)
            ->with('account_created', $wantsAccount);
    }

    /** GET /order/success/{order_number} */
    public function success(string $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        // Guests may only see the order they just placed; owners may always see theirs.
        $isJustPlaced = (int) session('last_order_id') === (int) $order->id;
        $isOwner      = Auth::check() && (int) Auth::id() === (int) $order->user_id;

        abort_unless($isJustPlaced || $isOwner, 404);

        return view('order-success', compact('order'));
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
            ];
        }

        if ($removed) {
            session()->put('cart', $cart);
        }

        return [$lines, round(array_sum(array_column($lines, 'total')), 2), $removed];
    }

    private function shippingFor(array $lines): float
    {
        return empty($lines) ? 0.0 : (float) config('shop.shipping_flat', 50);
    }
}
