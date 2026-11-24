<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\PkPhone;
use App\Support\RecentOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocialProofController extends Controller
{
    /** GET /recent-orders — real recent orders (first name + city only). */
    public function recentOrders()
    {
        return response()->json(RecentOrders::feed())->header('Cache-Control', 'private, max-age=300');
    }

    /** POST /subscribe — WhatsApp-updates opt-in from the footer. */
    public function subscribe(Request $request)
    {
        $phone = PkPhone::normalize($request->input('phone'));
        if (! preg_match(PkPhone::PATTERN, $phone)) {
            return $request->wantsJson()
                ? response()->json(['ok' => false, 'message' => 'Please enter a mobile number like 0300-1234567.'], 422)
                : back()->withErrors(['subscribe_phone' => 'Please enter a mobile number like 0300-1234567.']);
        }

        $now = now();
        DB::table('subscribers')->insertOrIgnore(['phone' => $phone, 'source' => 'footer', 'created_at' => $now, 'updated_at' => $now]);

        return $request->wantsJson()
            ? response()->json(['ok' => true, 'message' => "You're in! We'll send new arrivals and deals on WhatsApp."])
            : back()->with('success', "You're in! We'll send new arrivals and deals on WhatsApp.");
    }
}
