<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Response;

class LlmsTxtController extends Controller
{
    public function index(): Response
    {
        $lines = [];
        $lines[] = '# ' . config('app.name');
        $lines[] = '';
        $lines[] = '> Goods Essentials is an online store in Pakistan selling baby and mom essentials: baby clothing, feeding supplies, baby care and grooming products, toys, and newborn gift sets. Cash on Delivery is available across Pakistan.';
        $lines[] = '';
        $lines[] = '## Audience';
        $lines[] = 'Pakistani parents and families shopping for babies and young children, and people buying gifts for a newborn, baby shower, or aqiqa.';
        $lines[] = '';

        $categories = Category::whereNull('parent_id')->get();
        if ($categories->isNotEmpty()) {
            $lines[] = '## Categories';
            foreach ($categories as $category) {
                $lines[] = '- [' . $category->name . '](' . route('shop.category', $category->slug) . ')';
            }
            $lines[] = '';
        }

        $lines[] = '## Policies';
        $lines[] = '- Cash on Delivery: available for orders under Rs. 40,000 (orders above that require a partial advance payment). [Shipping Policy](' . route('shipping-policy') . ')';
        $lines[] = '- Delivery time: approximately 1-2 working days within Karachi, 4-5 working days to other cities in Pakistan.';
        $lines[] = '- Delivery charge: Rs. 200 for prepaid orders, Rs. 250 for Cash on Delivery orders.';
        $lines[] = '- Returns & exchanges: 14 days from delivery, item must be unused and in original packaging. [Return, Refund & Exchange Policy](' . route('return-refund-policy') . ')';
        $lines[] = '- [Privacy Policy](' . route('privacy-policy') . ')';
        $lines[] = '- [Terms & Conditions](' . route('terms-and-conditions') . ')';
        $lines[] = '';

        $lines[] = '## Contact';
        $contact = config('shop.contact', []);
        if (! empty($contact['phone'])) {
            $lines[] = '- Phone: ' . $contact['phone'];
        }
        if (! empty($contact['whatsapp'])) {
            $lines[] = '- WhatsApp: ' . $contact['whatsapp'];
        }
        if (! empty($contact['email'])) {
            $lines[] = '- Email: ' . $contact['email'];
        }
        if (! empty($contact['hours'])) {
            $lines[] = '- Support hours: ' . $contact['hours'];
        }
        $lines[] = '- [Contact form](' . route('contacts.index') . ')';
        $lines[] = '';

        $lines[] = '## Key pages';
        $lines[] = '- [Shop all products](' . route('shop') . ')';
        $lines[] = '- [About us](' . route('about') . ')';

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
