<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggleWishlist(Request $request)
    {
        $productId = $request->input('product_id');
        $user = Auth::user();

        // Check if the product is already in the wishlist
        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlistItem) {
            // Remove product from wishlist
            $wishlistItem->delete();
            return response()->json(['success' => 'Product removed from wishlist', 'action' => 'removed']);
        } else {
            // Add product to wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            return response()->json(['success' => 'Product added to wishlist', 'action' => 'added']);
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user(); // Get the authenticated user
        $productId = $request->input('product_id'); // Get the product ID from the request

        $wishList = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();
        $wishList->delete();

        return redirect()->back()->with('success', 'Product removed from wishlist');
    }
}
