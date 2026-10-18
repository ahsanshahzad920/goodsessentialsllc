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

        $products = Product::when($search, function ($query, $search) {
            $query->where('product_title', 'LIKE', "%{$search}%")
                ->orWhere('price', 'LIKE', "%{$search}%")
                ->orWhere('dis_price', 'LIKE', "%{$search}%")
                ->orWhere('product_description', 'LIKE', "%{$search}%");
        })
            ->when($categoryName, function ($query, $categoryName) {
                $query->whereHas('category', function ($query) use ($categoryName) {
                    $query->where('name', $categoryName)->orWhere('slug', $categoryName);
                });
            })
            ->paginate(12);

        SeoService::set(
            $search ? "Search results for \"{$search}\" | " . config('app.name') : 'Shop All Baby & Mom Products Online in Pakistan | ' . config('app.name'),
            'Browse baby clothes, feeding essentials, baby care, toys and gift sets online in Pakistan. Cash on Delivery, fast delivery & easy returns.',
            index: ! $search,
        );

        return view('shop', compact('categories', 'products', 'category'));
    }

    public function shopCategory(Request $request, Category $category)
    {
        $categories = Category::withCount('products')->get();

        $search = $request->input('q', $request->input('search'));

        $categoryIds = $category->children->isNotEmpty()
            ? $category->children->pluck('id')->push($category->id)
            : [$category->id];

        $products = Product::whereIn('category_id', $categoryIds)
            ->when($search, function ($query, $search) {
                $query->where('product_title', 'LIKE', "%{$search}%");
            })
            ->paginate(12);

        SeoService::forCategory($category);
        SeoService::itemList(collect($products->items()));
        SeoService::faq([
            'Is Cash on Delivery available for ' . $category->name . '?' => 'Yes. Cash on Delivery is available for orders under Rs. 40,000. Orders above that need a partial advance payment by bank transfer.',
            'What is the return policy?' => 'You can request a return or exchange within 14 days of delivery. Items must be unused and in their original packaging with tags attached.',
            'How long does delivery take?' => 'Delivery takes about 1-2 working days within Karachi and 4-5 working days to other cities in Pakistan.',
        ]);

        return view('shop', compact('categories', 'products', 'category'));
    }

    public function productDetails(Product $product)
    {
        $products = Product::where('category_id', $product->category_id)->get();

        SeoService::forProduct($product);
        SeoService::faq([
            'Is Cash on Delivery available for this product?' => 'Yes, Cash on Delivery is available for orders under Rs. 40,000.',
            'What is the return policy for this product?' => 'You can request a return or exchange within 14 days of delivery, provided the item is unused and in its original packaging.',
            'How long will delivery take?' => 'Estimated delivery is 1-2 working days within Karachi and 4-5 working days to other cities in Pakistan.',
        ]);

        return view('product-details', compact('product', 'products'));
    }
}
