<?php

namespace App\Support;

/**
 * Single source for the homepage FAQ. The visible accordion and the FAQPage
 * JSON-LD both read from here, so they can no longer drift apart (they were
 * two hand-copied arrays before).
 */
class StoreFaq
{
    /** @return array<string, string> question => answer */
    public static function home(): array
    {
        return [
            'Does Goods Essentials offer Cash on Delivery?' => 'Yes. Cash on Delivery is available across Pakistan for orders under Rs. 40,000, and you can open the parcel to check it before you pay. Orders above that amount require a partial advance payment by bank transfer.',
            'How long does delivery take?' => config('shop.delivery.message') . ' Delivery may take longer during major sales.',
            'Do you deliver all over Pakistan?' => 'Yes. We ship from Bahawalpur to every city in Pakistan, and delivery takes 1–3 working days.',
            'What is the delivery charge?' => 'Prepaid orders have a Rs. 200 delivery charge, and Cash on Delivery orders have a Rs. 250 delivery charge. Charges are shown at checkout before you place your order.',
            'Can I return or exchange a product?' => 'Yes. Contact us within 14 days of delivery to request a return or exchange. The item must be unused, in its original packaging, with tags and accessories intact.',
            'What happens if my order arrives damaged?' => 'Contact us as soon as possible and within 14 days of delivery. We may ask for photographs or an unboxing video, and we cover return shipping for verified damaged or incorrect items.',
        ];
    }
}
