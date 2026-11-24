<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Support\WebpImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Customer review submission. Reviews wait for admin approval before they
 * appear. "Verified buyer" is set only when the review matches a real order
 * containing this product (the shopper's account, or the phone number they
 * ordered with — the number is used for matching only and is not stored).
 */
class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:60'],
            'city'    => ['nullable', 'string', 'max:60'],
            'rating'  => ['required', 'integer', 'between:1,5'],
            'body'    => ['required', 'string', 'min:10', 'max:1500'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'photo'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'website' => ['nullable', 'max:0'], // honeypot: bots fill every field
        ], [
            'website.max' => 'Something went wrong. Please try again.',
        ]);

        $order = $this->matchingOrder($product, $data['phone'] ?? null);

        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('reviews', 'public');
            rescue(fn () => WebpImage::generate($photo), report: false);
        }

        Review::create([
            'product_id'  => $product->id,
            'order_id'    => $order?->id,
            'name'        => strip_tags($data['name']),
            'city'        => isset($data['city']) ? strip_tags($data['city']) : null,
            'rating'      => $data['rating'],
            'body'        => strip_tags($data['body']),
            'photo'       => $photo,
            'is_approved' => false,
            'is_featured' => false,
            'is_verified' => (bool) $order,
        ]);

        return redirect()->to(route('productDetails', $product->slug) . '#reviews')
            ->with('review_submitted', true)
            ->with('success', 'Thank you! Your review will appear after a quick check.');
    }

    private function matchingOrder(Product $product, ?string $phone): ?Order
    {
        $query = Order::where('status', '!=', 'cancelled')
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id));

        if (Auth::check()) {
            $byUser = (clone $query)->where('user_id', Auth::id())->latest()->first();
            if ($byUser) {
                return $byUser;
            }
        }

        $digits = preg_replace('/\D/', '', (string) $phone);
        if (strlen($digits) < 10) {
            return null;
        }
        // Compare on the last 10 digits so 0300…, +92300… and 92300… all match.
        $tail = substr($digits, -10);

        return $query->get(['id', 'phone'])
            ->first(fn ($o) => substr(preg_replace('/\D/', '', (string) $o->phone), -10) === $tail);
    }
}
