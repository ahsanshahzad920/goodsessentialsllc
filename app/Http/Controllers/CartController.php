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
        ]);

        $product  = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;
        $price    = Price::final($product);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = min(self::MAX_QTY, $cart[$product->id]['quantity'] + $quantity);
            $cart[$product->id]['product_price'] = $price; // keep price fresh
        } else {
            $cart[$product->id] = [
                'product_id'    => $product->id,
                'product_title' => $product->product_title,
                'product_price' => $price,
                'quantity'      => $quantity,
                'product_image' => $product->product_image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function incrementSession(Request $request)
    {
        $request->validate(['product_id' => 'required|integer']);

        $productId = (int) $request->product_id;
        $cart = session()->get('cart', []);

        if (! isset($cart[$productId])) {
            return response()->json(['message' => 'Item is not in your cart.'], 404);
        }

        $cart[$productId]['quantity'] = min(self::MAX_QTY, $cart[$productId]['quantity'] + 1);
        session()->put('cart', $cart);

        return $this->cartResponse($cart, $productId, 'Product quantity updated successfully');
    }

    public function decrementSession(Request $request)
    {
        $request->validate(['product_id' => 'required|integer']);

        $productId = (int) $request->product_id;
        $cart = session()->get('cart', []);

        if (! isset($cart[$productId])) {
            return response()->json(['message' => 'Item is not in your cart.'], 404);
        }

        // Never below 1 here; removing an item is done with the Remove button.
        if ($cart[$productId]['quantity'] > 1) {
            $cart[$productId]['quantity']--;
        }

        session()->put('cart', $cart);

        return $this->cartResponse($cart, $productId, 'Product quantity updated successfully');
    }

    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|integer']);

        $productId = (int) $request->input('product_id');
        $cart = session()->get('cart', []);

        if (! isset($cart[$productId])) {
            return redirect()->back()->with('error', 'Product not found in your cart.');
        }

        unset($cart[$productId]);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product removed from your cart.');
    }

    private function cartResponse(array $cart, int $productId, string $message)
    {
        $grandTotal = 0;
        foreach ($cart as $details) {
            $grandTotal += $details['quantity'] * $details['product_price'];
        }

        return response()->json([
            'message'    => $message,
            'quantity'   => $cart[$productId]['quantity'],
            'total'      => $cart[$productId]['quantity'] * $cart[$productId]['product_price'],
            'grandTotal' => $grandTotal,
        ]);
    }
}
