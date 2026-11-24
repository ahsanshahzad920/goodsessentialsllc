<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Price;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private const MAX_QTY = 99;

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:' . self::MAX_QTY,
            'color'      => 'nullable|string|max:7',
        ]);

        $product  = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;
        $price    = Price::final($product);

        // Products with colors: the customer must pick one of the product's own colors.
        $color = null;

        if (! empty($product->colors)) {
            $color = collect($product->colors)->firstWhere('hex', strtolower((string) $request->color));

            if (! $color) {
                return $this->fail($request, 'color', 'Please choose a color.');
            }
        }

        // Optional inventory: only products with a stock number are limited.
        $inCart = collect(session('cart', []))->where('product_id', $product->id)->sum('quantity');
        $available = \App\Support\Stock::available($product, (int) $inCart);
        if ($available !== null && $quantity > $available) {
            return $this->fail($request, 'quantity', $available > 0
                ? "Only {$available} more of this item can be added."
                : ($inCart ? 'You already have all remaining stock of this item in your cart.' : 'Sorry, this item is sold out.'));
        }

        // Same product in two colors = two cart lines
        $key = $color ? $product->id . '-' . ltrim($color['hex'], '#') : (string) $product->id;

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = min(self::MAX_QTY, $cart[$key]['quantity'] + $quantity);
            $cart[$key]['product_price'] = $price; // keep price fresh
            // Bug fix: this wrote to $cart[$product->id] instead of $cart[$key].
            // For a colorless product the two are the same string, so it was
            // never noticed -- but for a colored item already in the cart,
            // $product->id (e.g. "5") is a different key from $key (e.g.
            // "5-3b82f6"), so this was creating a second, malformed cart
            // entry with only a product_slug and nothing else.
            $cart[$key]['product_slug'] = $product->slug;
        } else {
            $cart[$key] = [
                'product_id'    => $product->id,
                'product_title' => $product->product_title,
                'product_price' => $price,
                'quantity'      => $quantity,
                'product_image' => $product->product_image,
                'color_name'    => $color['name'] ?? null,
                'color_hex'     => $color['hex'] ?? null,
                'product_slug'  => $product->slug,
            ];
        }

        session()->put('cart', $cart);

        // Server-side twin of the browser AddToCart (same event_id => deduplicated).
        $eventId = (string) $request->input('event_id');
        if (preg_match('/^atc_[A-Za-z0-9-]{8,60}$/', $eventId)) {
            \App\Services\MetaConversions::send('AddToCart', $eventId, [
                'value' => round($price * $quantity, 2), 'content_type' => 'product',
                'content_ids' => [(string) $product->id], 'content_name' => $product->product_title,
                'contents' => [['id' => (string) $product->id, 'quantity' => $quantity, 'item_price' => (float) $price]],
            ], $request);
        }

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout');
        }

        if ($request->wantsJson()) {
            // AJAX add from product cards: the page stays put (fly-to-cart + toast).
            return response()->json([
                'ok'      => true,
                'count'   => collect($cart)->sum('quantity'),
                'message' => 'Added to cart',
                'cart_url' => route('cart'),
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!')->with('cart_added', true);
    }

    private function fail(Request $request, string $field, string $message)
    {
        return $request->wantsJson()
            ? response()->json(['ok' => false, 'message' => $message], 422)
            : back()->withInput()->withErrors([$field => $message]);
    }

    public function incrementSession(Request $request)
    {
        $key  = $this->itemKey($request);
        $cart = session()->get('cart', []);

        if (! isset($cart[$key])) {
            return response()->json(['message' => 'Item is not in your cart.'], 404);
        }

        // Respect tracked stock across all colour lines of this product.
        $product = Product::find($cart[$key]['product_id'] ?? 0);
        if ($product && \App\Support\Stock::tracked($product)) {
            $inCart = collect($cart)->where('product_id', $product->id)->sum('quantity');
            if ($inCart + 1 > $product->stock_quantity) {
                return response()->json(['message' => "Only {$product->stock_quantity} in stock."], 422);
            }
        }

        $cart[$key]['quantity'] = min(self::MAX_QTY, $cart[$key]['quantity'] + 1);
        session()->put('cart', $cart);

        return $this->cartResponse($cart, $key, 'Product quantity updated successfully');
    }

    public function decrementSession(Request $request)
    {
        $key  = $this->itemKey($request);
        $cart = session()->get('cart', []);

        if (! isset($cart[$key])) {
            return response()->json(['message' => 'Item is not in your cart.'], 404);
        }

        // Never below 1 here; removing an item is done with the Remove button.
        if ($cart[$key]['quantity'] > 1) {
            $cart[$key]['quantity']--;
        }

        session()->put('cart', $cart);

        return $this->cartResponse($cart, $key, 'Product quantity updated successfully');
    }

    public function remove(Request $request)
    {
        $key  = $this->itemKey($request);
        $cart = session()->get('cart', []);

        if (! isset($cart[$key])) {
            return redirect()->back()->with('error', 'Product not found in your cart.');
        }

        unset($cart[$key]);
        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'count' => collect($cart)->sum('quantity')]);
        }

        return redirect()->back()->with('success', 'Product removed from your cart.');
    }

    /** Cart line key. Older pages still send product_id, which is the key for products without colors. */
    private function itemKey(Request $request): string
    {
        $request->validate([
            'item_key'   => 'required_without:product_id|nullable|string|max:40',
            'product_id' => 'required_without:item_key|nullable|integer',
        ]);

        return (string) ($request->input('item_key') ?: $request->input('product_id'));
    }

    /** GET /cart/drawer — HTML for the slide-in cart (lines, free-delivery bar, add-ons). */
    public function drawer()
    {
        return response()->view('partials.cart-drawer', \App\Support\CartDrawer::data())
            ->header('Cache-Control', 'no-store');
    }

    private function cartResponse(array $cart, string $key, string $message)
    {
        $grandTotal = 0;
        foreach ($cart as $details) {
            $grandTotal += $details['quantity'] * $details['product_price'];
        }

        return response()->json([
            'message'    => $message,
            'quantity'   => $cart[$key]['quantity'],
            'total'      => $cart[$key]['quantity'] * $cart[$key]['product_price'],
            'grandTotal' => $grandTotal,
            'count'      => collect($cart)->sum('quantity'),
        ]);
    }
}
