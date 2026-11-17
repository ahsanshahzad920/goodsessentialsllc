<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\SeoService;
use App\Support\{Price, Stock};
use Illuminate\Http\Request;

/**
 * Gift box builder: pick 3–5 items, see the live total, add them all to the
 * cart in one go with an optional gift message (prefilled into the checkout
 * notes). No bundle discount or gift-wrap fee is applied — pricing for those
 * is the owner's call and is not invented here.
 */
class GiftBoxController extends Controller
{
    public const MIN = 3;
    public const MAX = 5;

    public function show()
    {
        SeoService::set(
            'Build a Baby Gift Box | ' . config('app.name'),
            'Pick 3 to 5 baby essentials for a newborn or Aqiqa gift — delivered anywhere in Pakistan with Cash on Delivery.',
        );

        return view('gift-box', [
            'products' => $this->eligible()->get(),
            'min' => self::MIN,
            'max' => self::MAX,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'items'   => ['required', 'array', 'min:' . self::MIN, 'max:' . self::MAX],
            'items.*' => ['integer', 'distinct'],
            'message' => ['nullable', 'string', 'max:300'],
        ], [
            'items.min' => 'Please pick at least ' . self::MIN . ' items for your gift box.',
            'items.max' => 'A gift box can hold up to ' . self::MAX . ' items.',
        ]);

        $products = $this->eligible()->whereIn('id', $data['items'])->get()->keyBy('id');
        if ($products->count() !== count($data['items'])) {
            return back()->withErrors(['items' => 'One of the items is no longer available. Please choose again.'])->withInput();
        }

        $cart = session('cart', []);
        foreach ($products as $p) {
            $color = count($p->colors ?? []) === 1 ? $p->colors[0] : null;
            $key = $color ? $p->id . '-' . ltrim($color['hex'], '#') : (string) $p->id;
            $qty = ($cart[$key]['quantity'] ?? 0) + 1;

            $inCart = collect($cart)->where('product_id', $p->id)->sum('quantity');
            if (Stock::tracked($p) && $inCart + 1 > $p->stock_quantity) {
                return back()->withErrors(['items' => "\"{$p->product_title}\" doesn't have enough stock left."])->withInput();
            }

            $cart[$key] = [
                'product_id'    => $p->id,
                'product_title' => $p->product_title,
                'product_price' => Price::final($p),
                'quantity'      => $qty,
                'product_image' => $p->product_image,
                'color_name'    => $color['name'] ?? null,
                'color_hex'     => $color['hex'] ?? null,
                'product_slug'  => $p->slug,
            ];
        }
        session()->put('cart', $cart);

        $note = 'GIFT BOX: ' . $products->pluck('product_title')->implode(', ') . '.';
        if (! empty($data['message'])) {
            $note .= ' Gift message: "' . strip_tags($data['message']) . '"';
        }
        session()->put('checkout_note', $note);

        return redirect()->route('checkout')->with('success', 'Your gift box is in the cart.');
    }

    /** Items that can go into a box without choosing a colour, and are in stock. */
    private function eligible()
    {
        return Product::forBabySections()
            ->withCardData()
            ->where(fn ($q) => $q->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0))
            ->where(function ($q) {
                $q->whereNull('colors')->orWhereRaw('JSON_LENGTH(colors) <= 1');
            });
    }
}
