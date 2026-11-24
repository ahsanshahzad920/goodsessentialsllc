<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SearchTerm;
use App\Support\WebpImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * GET /search/suggest?q=…  — instant results for the header search box.
 * Empty q returns real popular searches (if any) and the category list.
 */
class SearchSuggestController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'products'   => [],
                'popular'    => SearchTerm::popular(),
                'categories' => Category::navItems()->map(fn ($c) => [
                    'name' => $c['label'] ?? $c['name'],
                    'url'  => route('shop.category', $c['slug']),
                ])->values(),
            ])->header('Cache-Control', 'private, max-age=300');
        }

        $q = mb_substr($q, 0, 60);

        $products = Product::search($q)
            ->reorder()
            // Title prefix matches first, then other title matches, then SKU/description hits.
            ->orderByRaw('CASE WHEN product_title LIKE ? THEN 0 WHEN product_title LIKE ? THEN 1 ELSE 2 END', [
                addcslashes($q, '%_\\') . '%', '%' . addcslashes($q, '%_\\') . '%',
            ])
            ->latest()
            ->take(6)
            ->get(['id', 'product_title', 'slug', 'price', 'dis_price', 'product_image']);

        $total = Product::search($q)->count();

        return response()->json([
            'products' => $products->map(function (Product $p) {
                $price = (float) str_replace(',', '', (string) $p->price);
                $final = (float) calculateDiscountPrice($price, (float) $p->dis_price);

                return [
                    'title' => $p->product_title,
                    'url'   => route('productDetails', $p->slug),
                    'image' => WebpImage::fit($p->product_image, 240) ?? asset('images/product-placeholder.svg'),
                    'price' => money($final ?: $price),
                    'was'   => $final > 0 && $final < $price ? money($price) : null,
                ];
            })->values(),
            'total'   => $total,
            'all_url' => route('shop', ['q' => $q]),
        ])->header('Cache-Control', 'private, max-age=60');
    }
}
