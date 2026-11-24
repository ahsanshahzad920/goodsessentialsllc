<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\SeoService;
use App\Support\HomePage;
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

        return view('index', [
            'heroSlides'   => HomePage::heroSlides(),
            'bubbles'      => HomePage::categoryBubbles(),
            'deals'        => HomePage::deals(),
            'bestSellers'  => HomePage::bestSellers(),
            'newArrivals'  => HomePage::newArrivals(),
            'ageBuckets'   => HomePage::ageBuckets(),
            'giftProducts' => HomePage::giftProducts(),
            'testimonials' => Review::showcase()->with('product')->take(6)->get(),
            'homeFaqs'     => $homeFaqs,
        ]);
    }
}
