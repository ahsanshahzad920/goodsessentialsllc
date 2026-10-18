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
            'Baby Products Online in Pakistan | Goods Essentials',
            'Shop safe, quality baby clothes, breast pumps, baby care & newborn gift sets online in Pakistan. Cash on Delivery, fast delivery & easy returns.',
        );

        SeoService::faq([
            'Does Goods Essentials offer Cash on Delivery?' => 'Yes. Cash on Delivery is available across Pakistan for orders under Rs. 40,000. Orders above that amount require a partial advance payment by bank transfer.',
            'How long does delivery take?' => 'Delivery takes about 1-2 working days within Karachi and 4-5 working days to other cities in Pakistan. Delivery may take longer during major sales.',
            'What is the delivery charge?' => 'Prepaid orders have a Rs. 200 delivery charge, and Cash on Delivery orders have a Rs. 250 delivery charge. Charges are shown at checkout before you place your order.',
            'Can I return or exchange a product?' => 'Yes. Contact us within 14 days of delivery to request a return or exchange. The item must be unused, in its original packaging, with tags and accessories intact.',
            'What happens if my order arrives damaged?' => 'Contact us as soon as possible and within 14 days of delivery. We may ask for photographs or an unboxing video, and we cover return shipping for verified damaged or incorrect items.',
            'Do you deliver outside Karachi?' => 'Yes, we deliver across Pakistan. Delivery outside Karachi takes approximately 4-5 working days.',
        ]);

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
