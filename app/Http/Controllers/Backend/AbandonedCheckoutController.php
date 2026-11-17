<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCheckout;

/** Follow-up list: checkouts with a phone number but no order (yet). */
class AbandonedCheckoutController extends Controller
{
    public function index()
    {
        return view('backend.abandoned.index', [
            // Give shoppers 30 minutes to finish before they show up here.
            'rows' => AbandonedCheckout::whereNull('order_id')
                ->where('updated_at', '<=', now()->subMinutes(30))
                ->latest('updated_at')->paginate(30),
        ]);
    }

    public function contacted(AbandonedCheckout $abandoned)
    {
        $abandoned->update(['contacted_at' => now()]);

        return back()->with('success', 'Marked as contacted.');
    }
}
