<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        // Validate product ID and quantity
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Fetch the product
        $product = Product::findOrFail($request->product_id);

        // Add product to the cart (use session, database, or a cart package like Laravel Shopping Cart)
        // For simplicity, let's assume you're using a session-based cart
        $cart = session()->get('cart', []);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'product_title' => $product->product_title,
                'product_price' => $product->dis_price ?? $product->price,
                'quantity' => $quantity,
                'product_image' => $product->product_image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function incrementSession(Request $request)
    {
        $productId = $request->product_id;

        // Check if product exists in session
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            // If product doesn't exist, you can initialize it
            $cart[$productId] = [
                'quantity' => 1,
                // Add other product details if needed
            ];
        }

        // Total
        $newTotal = $cart[$productId]['product_price'] *  $cart[$productId]['quantity'];

        // Grand Total
        $grandTotal = 0;
        foreach ($cart as $details) {
            $grandTotal += $details['quantity'] * $details['product_price'];
        }

        session()->put('cart', $cart);

        return response()->json(['message' => 'Product quantity updated successfully', 'quantity' => $cart[$productId]['quantity'], 'total' => $newTotal, 'grandTotal' => $grandTotal]);
    }

    public function decrementSession(Request $request)
    {
        $productId = $request->product_id;

        // Retrieve cart from session
        $cart = session()->get('cart', []);

        // Check if product exists in the session
        if (isset($cart[$productId])) {
            // Decrease the quantity, but don't allow it to go below 1
            if ($cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
            } else {
                // Optionally, you can remove the product from the cart if quantity is 1
                unset($cart[$productId]);
            }
        }

        // Total
        $newTotal = $cart[$productId]['product_price'] *  $cart[$productId]['quantity'];

        // Grand Total
        $grandTotal = 0;
        foreach ($cart as $details) {
            $grandTotal += $details['quantity'] * $details['product_price'];
        }

        // Update session
        session()->put('cart', $cart);

        return response()->json(['message' => 'Product quantity decremented successfully', 'quantity' => $cart[$productId]['quantity'] ?? 0, 'total' => $newTotal, 'grandTotal' => $grandTotal]);
    }
}
