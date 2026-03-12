<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shopIndex(Request $request, string $categoryName = null)
    {
        $categories = Category::withCount('products')->get();

        if (is_null($categoryName)) {
            // Get search query and category ID
            $search = $request->input('search');
            $categoryName = $request->input('category');

            // Filter products based on search and category name
            $products = Product::when($search, function ($query, $search) {
                $query->where('product_title', 'LIKE', "%{$search}%")
                    ->orWhere('price', 'LIKE', "%{$search}%")
                    ->orWhere('dis_price', 'LIKE', "%{$search}%")
                    ->orWhere('product_description', 'LIKE', "%{$search}%");
            })
                ->when($categoryName, function ($query, $categoryName) {
                    $query->whereHas('category', function ($query) use ($categoryName) {
                        $query->where('name', 'LIKE', "%{$categoryName}%");
                    });
                })
                ->paginate(12);
        } else {
            $products = Product::whereHas('category', function ($query) use ($categoryName) {
                $query->where('name', 'LIKE', "%{$categoryName}%");
            })->paginate(12);
        }

        return view('shop', compact('categories', 'products'));
    }


    public function productDetails(string $id)
    {
        $product = Product::with('category')->find($id);
        $products = Product::where('category_id', $product->category_id)->get();
        return view('product-details', compact('product', 'products'));
    }
}
