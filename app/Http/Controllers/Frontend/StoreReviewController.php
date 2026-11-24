<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Store-wide reviews ("What Our Customers Say"): the /reviews page and its
 * "Leave a review" form. Every submission waits for admin approval
 * (Admin → Reviews). "Verified buyer" is set only when the reviewer matches a
 * real, non-cancelled order: their account, or the phone number they ordered
 * with (used for matching only, never stored or shown).
 */
class StoreReviewController extends Controller
{
    public function index()
    {
        return view('reviews', [
            'reviews' => Review::showcase()->with('product:id,product_title,slug,product_image')->paginate(12),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:60'],
            'city'    => ['nullable', 'string', 'max:60'],
            'rating'  => ['required', 'integer', 'between:1,5'],
            'body'    => ['required', 'string', 'min:10', 'max:1500'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'max:0'], // honeypot: bots fill every field
        ], [
            'rating.required' => 'Please choose a star rating.',
            'body.min'        => 'Please write at least a few words (10+ characters).',
            'website.max'     => 'Something went wrong. Please try again.',
        ]);

        $order = $this->matchingOrder($data['phone'] ?? null);

        Review::create([
            'product_id'  => null,
            'order_id'    => $order?->id,
            'name'        => strip_tags($data['name']),
            'city'        => isset($data['city']) ? strip_tags($data['city']) : null,
            'rating'      => $data['rating'],
            'body'        => strip_tags($data['body']),
            'is_approved' => false,
            'is_featured' => false,
            'is_verified' => (bool) $order,
        ]);

        return redirect()->to(route('store-reviews.index') . '#write')
            ->with('review_submitted', true);
    }

    private function matchingOrder(?string $phone): ?Order
    {
        $query = Order::where('status', '!=', 'cancelled');

        if (Auth::check() && ($byUser = (clone $query)->where('user_id', Auth::id())->latest()->first())) {
            return $byUser;
        }

        $digits = preg_replace('/\D/', '', (string) $phone);
        if (strlen($digits) < 10) {
            return null;
        }
        // Compare the last 10 digits so 0300…, +92300… and 92300… all match.
        $tail = substr($digits, -10);

        // Older orders may store the number with spaces/dashes, so match in PHP.
        return $query->latest()->get(['id', 'phone'])
            ->first(fn ($o) => substr(preg_replace('/\D/', '', (string) $o->phone), -10) === $tail);
    }
}
