<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
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

        return view('shop', compact('categories', 'products', 'category'));
    }

    public function productDetails(Product $product)
    {
        $products = Product::where('category_id', $product->category_id)->get();
        return view('product-details', compact('product', 'products'));
    }
}
