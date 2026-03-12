<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function indexPage()
    {
        // Fetch products where category is 'Baby Products'
        $babyProducts = Product::latest()->take(24)->get();
        // $babyProducts = Product::whereHas('category', function ($query) {
        //     $query->where('name', 'Baby Products');
        // })->get();

        // Fetch products where category is NOT 'Baby Products'
        $otherProducts = Product::whereHas('category', function ($query) {
            $query->where('name', '!=', 'Baby Products');
        })->get();

        $products = Product::all();

        $latestProducts = Product::latest()->take(9)->get();

        return view('index', compact('babyProducts', 'otherProducts', 'products', 'latestProducts'));
    }
}
