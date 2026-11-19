<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SearchTerm;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('backend.settings.edit', [
            'settings'    => Settings::all(),
            'heroSlides'  => array_pad(Settings::json('hero_slides'), 3, []),
            'products'    => Product::reorder()->orderBy('product_title')->get(['id', 'product_title', 'dis_price']),
            'topSearches' => SearchTerm::orderByDesc('hits')->take(15)->get(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'free_delivery_threshold' => ['nullable', 'numeric', 'min:1', 'max:1000000'],
            'announcement_enabled'    => ['nullable', 'boolean'],
            'recent_orders_enabled'   => ['nullable', 'boolean'],
            'deals_ends_at'           => ['nullable', 'date'],
            'hero'                    => ['nullable', 'array', 'max:3'],
            'hero.*.product_id'       => ['nullable', 'integer', 'exists:products,id'],
            'hero.*.eyebrow'          => ['nullable', 'string', 'max:40'],
            'hero.*.badge'            => ['nullable', 'string', 'max:20'],
            'hero.*.headline'         => ['nullable', 'string', 'max:80'],
        ]);

        $hero = collect($data['hero'] ?? [])
            ->filter(fn ($s) => ! empty($s['product_id']))
            ->map(fn ($s) => [
                'product_id' => (int) $s['product_id'],
                'eyebrow'    => trim($s['eyebrow'] ?? ''),
                'badge'      => trim($s['badge'] ?? ''),
                'headline'   => trim($s['headline'] ?? ''),
            ])->values()->all();

        Settings::set([
            'free_delivery_threshold' => $data['free_delivery_threshold'] ?? null,
            'announcement_enabled'    => $request->boolean('announcement_enabled') ? '1' : '0',
            'recent_orders_enabled'   => $request->boolean('recent_orders_enabled') ? '1' : '0',
            'deals_ends_at'           => $data['deals_ends_at'] ?? null,
            'hero_slides'             => $hero ? json_encode($hero) : null,
        ]);

        // Homepage caches that depend on these settings.
        Cache::forget('home.best_sellers');
        Cache::forget('recent_orders_feed');

        return redirect()->route('admin.settings.edit')->with('success', 'Settings saved. They are live on the store now.');
    }
}
