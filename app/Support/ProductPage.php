<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/** Data for the product page; real data only. */
class ProductPage
{
    /** Pairs must be bought together in at least this many orders to be suggested. */
    public const FBT_MIN_ORDERS = 2;

    /**
     * Split the admin's description HTML at its <h3> headings so sections such
     * as "Key Features" or "Suitable Age" can live in their own accordion.
     *
     * @return array{intro:string, sections: array<string,string>}
     */
    public static function descriptionSections(?string $html): array
    {
        $html = (string) $html;
        $parts = preg_split('/<h3[^>]*>(.*?)<\/h3>/is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $intro = trim($parts[0] ?? '');
        $sections = [];
        for ($i = 1; $i + 1 < count($parts); $i += 2) {
            $title = trim(html_entity_decode(strip_tags($parts[$i])));
            $body = trim($parts[$i + 1]);
            if ($title !== '' && $body !== '') {
                $sections[$title] = $body;
            }
        }

        return ['intro' => $intro, 'sections' => $sections];
    }

    /** List items of an HTML section (for icon bullets), or [] if it is not a list. */
    public static function listItems(string $html): array
    {
        preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $m);

        return array_values(array_filter(array_map(fn ($li) => trim(strip_tags($li)), $m[1] ?? [])));
    }

    /**
     * Delivery window for an order placed now: 1–3 working days (Mon–Sat),
     * in Pakistan time. Orders after 6 pm count from the next working day.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function deliveryWindow(?Carbon $now = null): array
    {
        $now = ($now ?? now())->copy()->setTimezone('Asia/Karachi');
        $start = $now->hour >= 18 ? $now->copy()->addDay() : $now->copy();

        $add = function (Carbon $from, int $days) {
            $d = $from->copy();
            while ($days > 0) {
                $d->addDay();
                if (! $d->isSunday()) {
                    $days--;
                }
            }

            return $d;
        };

        return [$add($start, 1), $add($start, 3)];
    }

    /** Approved reviews + rating breakdown for one product. */
    public static function reviews(Product $product): array
    {
        $reviews = Review::approved()->where('product_id', $product->id)->latest()->take(20)->get();

        $counts = Review::approved()->where('product_id', $product->id)
            ->selectRaw('rating, COUNT(*) AS n')->groupBy('rating')->pluck('n', 'rating');

        $total = (int) $counts->sum();
        $avg = $total ? round($counts->map(fn ($n, $r) => $n * $r)->sum() / $total, 1) : null;

        return [
            'items'     => $reviews,
            'total'     => $total,
            'average'   => $avg,
            'breakdown' => collect([5, 4, 3, 2, 1])->mapWithKeys(fn ($r) => [$r => (int) ($counts[$r] ?? 0)])->all(),
        ];
    }

    /** Products really bought together with this one (>= FBT_MIN_ORDERS orders). */
    public static function boughtTogether(Product $product, int $limit = 2): Collection
    {
        $ids = DB::table('order_items as a')
            ->join('order_items as b', function ($j) {
                $j->on('a.order_id', '=', 'b.order_id')->on('a.product_id', '!=', 'b.product_id');
            })
            ->where('a.product_id', $product->id)
            ->whereNotNull('b.product_id')
            ->groupBy('b.product_id')
            ->havingRaw('COUNT(DISTINCT a.order_id) >= ?', [self::FBT_MIN_ORDERS])
            ->orderByRaw('COUNT(DISTINCT a.order_id) DESC')
            ->limit($limit)
            ->pluck('b.product_id');

        return $ids->isEmpty()
            ? collect()
            : Product::withCardData()->whereIn('id', $ids)->get()->filter(fn ($p) => ! Stock::soldOut($p))->values();
    }
}
