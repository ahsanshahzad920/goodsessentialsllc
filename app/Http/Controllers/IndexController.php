<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Services\SeoService;
use App\Support\StoreFaq;

class IndexController extends Controller
{
    public function indexPage()
    {
        SeoService::set(
            'Baby Products at Wholesale Rates | Goods Essentials',
            'Quality baby clothes, feeding supplies, baby care & gift sets at wholesale prices across Pakistan. Cash on Delivery, fast delivery & easy returns.',
        );

        $homeFaqs = StoreFaq::home();
        SeoService::faq($homeFaqs);

        // Baby sections exclude non-baby categories (e.g. Mom & Wellness:
        // the massage gun) — see shop.homepage_exclude_categories.
        $babyProducts = Product::forBabySections()->take(24)->get();

        // Deals = products that actually have a discount, biggest discount
        // first. dis_price is a string column holding a percentage, so it is
        // cast before sorting (a plain sort put "9" above "40").
        $latestProducts = Product::forBabySections()
            ->where('dis_price', '>', 0)
            ->reorder()->orderByRaw('CAST(dis_price AS DECIMAL(6,2)) DESC')->latest()
            ->take(9)->get();

        $homeTiles = Category::homeTiles();

        $testimonials = Review::approved()->featured()->latest()->take(6)->get();

        return view('index', compact('babyProducts', 'latestProducts', 'homeTiles', 'testimonials', 'homeFaqs'));
    }
}
