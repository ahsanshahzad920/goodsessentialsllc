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
                return back()->withInput()->withErrors(['color' => 'Please choose a color.']);
            }
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

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout');
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function incrementSession(Request $request)
    {
        $key  = $this->itemKey($request);
        $cart = session()->get('cart', []);

        if (! isset($cart[$key])) {
            return response()->json(['message' => 'Item is not in your cart.'], 404);
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
        ]);
    }
}
