<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\SeoService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function indexPage()
    {
        SeoService::set(
            'Baby Products at Wholesale Rates | Goods Essentials',
            'Quality baby clothes, feeding supplies, baby care & gift sets at wholesale prices across Pakistan. Cash on Delivery, fast delivery & easy returns.',
        );

        SeoService::faq([
            'Does Goods Essentials offer Cash on Delivery?' => 'Yes. Cash on Delivery is available across Pakistan for orders under Rs. 40,000. Orders above that amount require a partial advance payment by bank transfer.',
            'How long does delivery take?' => config('shop.delivery.message') . ' Delivery may take longer during major sales.',
            'What is the delivery charge?' => 'Prepaid orders have a Rs. 200 delivery charge, and Cash on Delivery orders have a Rs. 250 delivery charge. Charges are shown at checkout before you place your order.',
            'Can I return or exchange a product?' => 'Yes. Contact us within 14 days of delivery to request a return or exchange. The item must be unused, in its original packaging, with tags and accessories intact.',
            'What happens if my order arrives damaged?' => 'Contact us as soon as possible and within 14 days of delivery. We may ask for photographs or an unboxing video, and we cover return shipping for verified damaged or incorrect items.',
            'Do you deliver outside Karachi?' => 'Yes, we deliver to every city in Pakistan, not just Karachi — ' . lcfirst(config('shop.delivery.message')),
        ]);

        $babyProducts = Product::latest()->take(24)->get();

        // Bug fix: this used to be Product::latest()->take(9) — identical to
        // $babyProducts, just a shorter slice of the same "newest first" list,
        // so "Popular Picks" and "Deals of the Week" showed the same products
        // in the same order. Deals now means what it says: products that
        // actually have a discount, biggest discount first.
        $latestProducts = Product::where('dis_price', '>', 0)->orderByDesc('dis_price')->take(9)->get();

        return view('index', compact('babyProducts', 'latestProducts'));
    }
}
