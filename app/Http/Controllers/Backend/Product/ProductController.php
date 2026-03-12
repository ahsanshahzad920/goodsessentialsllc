<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('backend.products.index', compact('products'));
    }

    // Show form to create new product
    public function create()
    {
        $categories = Category::all();
        return view('backend.products.create', compact('categories'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'product_title' => 'required',
            'product_description' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'dis_price' => 'required|numeric',
            'product_image' => 'mimes:jpeg,png,jpg,gif,bmp,svg,webp,avif|max:10240',
        ]);

        // Handle image upload
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public'); // Store in storage/app/public/products
        } else {
            $imagePath = null;
        }

        Product::create([
            'product_title' => $request->product_title,
            'product_description' => $request->product_description,
            'product_image' => $imagePath,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'dis_price' => $request->dis_price,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Show a single product
    public function show(Product $product)
    {
        return view('backend.products.show', compact('product'));
    }

    // Show form to edit product
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('backend.products.edit', compact('product', 'categories'));
    }

    // Update a product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_title' => 'required',
            'product_description' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'dis_price' => 'required|numeric',
            'product_image' => 'mimes:jpeg,png,jpg,gif,bmp,svg,webp,avif|max:10240',
        ]);

        // Handle image upload
        if ($request->hasFile('product_image')) {
            // Delete the old image if it exists
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }

            // Store the new image
            $imagePath = $request->file('product_image')->store('products', 'public');
        } else {
            $imagePath = $product->product_image;
        }

        $product->update([
            'product_title' => $request->product_title,
            'product_description' => $request->product_description,
            'product_image' => $imagePath,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'dis_price' => $request->dis_price,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Delete a product
    public function destroy(Product $product)
    {
        // Delete the product image if it exists
        if ($product->product_image) {
            Storage::disk('public')->delete($product->product_image);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
