<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\SeoService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shopIndex(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $category = null;

        $search = $request->input('q', $request->input('search'));
        $categoryName = $request->input('category');
        $age = \App\Support\AgeRange::valid($request->input('age')) ? $request->input('age') : null;

        $products = Product::withCardData()->search($search)
            ->forAge($age)
            ->when($categoryName, function ($query, $categoryName) {
                $query->whereHas('category', function ($query) use ($categoryName) {
                    $query->where('name', $categoryName)->orWhere('slug', $categoryName);
                });
            })
            ->tap(fn ($q) => $this->applySort($q, $request->input('sort')))
            ->paginate(12)
            ->withQueryString();

        // Real search data for "popular searches" + the admin report.
        // First page only, so paging through results is not counted again.
        if ($search && $products->currentPage() === 1) {
            \App\Models\SearchTerm::record($search, $products->total());
        }

        SeoService::set(
            $search ? "Search results for \"{$search}\" | " . config('app.name')
                : ($age ? 'Baby Products for ' . \App\Support\AgeRange::label($age) . ' | ' . config('app.name')
                : 'Shop All Baby & Mom Products Online in Pakistan | ' . config('app.name')),
            'Browse baby clothes, feeding essentials, baby care, toys and gift sets online in Pakistan. Cash on Delivery, fast delivery & easy returns.',
            index: ! $search && ! $age,
        );

        return view('shop', compact('categories', 'products', 'category', 'search', 'age'));
    }

    public function shopCategory(Request $request, Category $category)
    {
        $categories = Category::withCount('products')->get();

        $search = $request->input('q', $request->input('search'));

        $categoryIds = $category->children->isNotEmpty()
            ? $category->children->pluck('id')->push($category->id)
            : [$category->id];

        $products = Product::withCardData()->whereIn('category_id', $categoryIds)
            ->search($search)
            ->tap(fn ($q) => $this->applySort($q, $request->input('sort')))
            ->paginate(12)
            ->withQueryString();

        SeoService::forCategory($category);
        SeoService::itemList(collect($products->items()));
        SeoService::faq([
            'Is Cash on Delivery available for ' . $category->name . '?' => 'Yes. Cash on Delivery is available for orders under Rs. 40,000. Orders above that need a partial advance payment by bank transfer.',
            'What is the return policy?' => 'You can request a return or exchange within 14 days of delivery. Items must be unused and in their original packaging with tags attached.',
            'How long does delivery take?' => config('shop.delivery.message'),
        ]);

        return view('shop', compact('categories', 'products', 'category', 'search'));
    }

    public function productDetails(Product $product)
    {
        // Related products for "You may also like" (cards need gallery + review data).
        $products = Product::withCardData()->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)->take(8)->get();

        SeoService::forProduct($product);
        SeoService::faq([
            'Is Cash on Delivery available for this product?' => 'Yes, Cash on Delivery is available for orders under Rs. 40,000.',
            'What is the return policy for this product?' => 'You can request a return or exchange within 14 days of delivery, provided the item is unused and in its original packaging.',
            'How long will delivery take?' => config('shop.delivery.message'),
        ]);

        $product->loadMissing('images', 'category');

        // ViewContent: same id for the CAPI event and the browser Pixel.
        $viewEventId = \App\Services\MetaConversions::newEventId('vc');
        \App\Services\MetaConversions::send('ViewContent', $viewEventId, [
            'value' => (float) \App\Support\Price::final($product), 'content_type' => 'product',
            'content_ids' => [(string) $product->id], 'content_name' => $product->product_title,
        ], request());

        return view('product-details', [
            'product'         => $product,
            'products'        => $products,
            'reviewData'      => \App\Support\ProductPage::reviews($product),
            'boughtTogether'  => \App\Support\ProductPage::boughtTogether($product),
            'description'     => \App\Support\ProductPage::descriptionSections($product->product_description),
            'deliveryWindow'  => \App\Support\ProductPage::deliveryWindow(),
            'viewEventId'     => $viewEventId,
        ]);
    }

    /**
     * The shop page has always shown a Sort dropdown, but the value was never
     * applied (every listing came back newest-first). Price sorts use the
     * price the customer actually pays (price minus the dis_price percentage).
     */
    private function applySort($query, ?string $sort): void
    {
        $final = "CAST(price AS DECIMAL(12,2)) * (1 - COALESCE(CAST(NULLIF(dis_price, '') AS DECIMAL(6,2)), 0) / 100)";

        match ($sort) {
            'price_asc'  => $query->reorder()->orderByRaw("$final ASC"),
            'price_desc' => $query->reorder()->orderByRaw("$final DESC"),
            'name'       => $query->reorder()->orderBy('product_title'),
            default      => null, // latest: the model's global scope
        };
    }
}
