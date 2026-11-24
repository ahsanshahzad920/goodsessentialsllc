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
