<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Support\WebpImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Review moderation: approve / hide, feature on the homepage, delete. */
class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'pending');

        $reviews = Review::with('product:id,product_title,slug')
            ->when($filter === 'pending', fn ($q) => $q->where('is_approved', false))
            ->when($filter === 'approved', fn ($q) => $q->where('is_approved', true))
            ->latest()->paginate(20)->withQueryString();

        return view('backend.reviews.index', [
            'reviews' => $reviews,
            'filter'  => $filter,
            'pending' => Review::where('is_approved', false)->count(),
        ]);
    }

    /**
     * Add a real review the store received elsewhere (WhatsApp, phone, Instagram).
     * Goes live at once because the admin is entering it; only tick
     * "Verified buyer" when this person really ordered.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:60'],
            'city'        => ['nullable', 'string', 'max:60'],
            'rating'      => ['required', 'integer', 'between:1,5'],
            'body'        => ['required', 'string', 'min:10', 'max:1500'],
            'is_verified' => ['sometimes', 'boolean'],
        ]);

        Review::create([
            'product_id'  => null,
            'name'        => strip_tags($data['name']),
            'city'        => isset($data['city']) ? strip_tags($data['city']) : null,
            'rating'      => $data['rating'],
            'body'        => strip_tags($data['body']),
            'is_approved' => true,
            'is_featured' => false,
            'is_verified' => $request->boolean('is_verified'),
        ]);

        return redirect()->route('admin.reviews.index', ['filter' => 'approved'])->with('success', 'Review added and visible on the site.');
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'is_approved' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
        ]);
        $review->update($data);

        return back()->with('success', 'Review updated.');
    }

    public function destroy(Review $review)
    {
        if ($review->photo) {
            Storage::disk('public')->delete($review->photo);
            WebpImage::delete($review->photo);
        }
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
