<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Meta Conversions API — the server-side twin of the browser Pixel.
 *
 * Every event is sent with the SAME event_id the browser Pixel uses, so Meta
 * counts it once (deduplication) while still receiving it when ad blockers
 * or iOS stop the browser event. Requests go out after the response has been
 * sent (dispatch()->afterResponse()), so shoppers never wait on Meta.
 * Completely inert until META_PIXEL_ID and META_CAPI_TOKEN are set.
 */
class MetaConversions
{
    public static function enabled(): bool
    {
        return (bool) (config('tracking.meta_pixel_id') && config('tracking.meta_capi_token'));
    }

    /** A fresh id to share between the server event and the browser Pixel call. */
    public static function newEventId(string $prefix): string
    {
        return $prefix . '_' . Str::uuid();
    }

    /**
     * @param array $custom  value, currency, content_ids, contents, num_items…
     * @param array $user    raw (unhashed) em / ph / fn / ln / ct — hashed here
     */
    public static function send(string $event, string $eventId, array $custom, Request $request, array $user = []): void
    {
        if (! self::enabled()) {
            return;
        }

        $payload = [
            'data' => [[
                'event_name'       => $event,
                'event_time'       => time(),
                'event_id'         => $eventId,
                'action_source'    => 'website',
                'event_source_url' => $request->headers->get('referer') ?: $request->fullUrl(),
                'user_data'        => self::userData($request, $user),
                'custom_data'      => $custom + ['currency' => 'PKR'],
            ]],
        ];
        if ($code = config('tracking.meta_test_event_code')) {
            $payload['test_event_code'] = $code;
        }

        $url = sprintf('https://graph.facebook.com/%s/%s/events', config('tracking.meta_graph_version'), config('tracking.meta_pixel_id'));
        $token = config('tracking.meta_capi_token');

        dispatch(function () use ($url, $token, $payload, $event) {
            try {
                $res = Http::timeout(4)->asJson()->post($url . '?access_token=' . urlencode($token), $payload);
                if ($res->failed()) {
                    Log::warning("Meta CAPI {$event} failed", ['status' => $res->status(), 'body' => Str::limit($res->body(), 300)]);
                }
            } catch (\Throwable $e) {
                Log::warning("Meta CAPI {$event} error: " . $e->getMessage());
            }
        })->afterResponse();
    }

    /** Purchase for a just-placed order, with hashed customer match keys. */
    public static function purchase(Order $order, Request $request): void
    {
        $order->loadMissing('items');
        self::send('Purchase', 'purchase_' . $order->id, [
            'value'        => (float) $order->total,
            'content_type' => 'product',
            'content_ids'  => $order->items->pluck('product_id')->filter()->map(fn ($id) => (string) $id)->values()->all(),
            'contents'     => $order->items->map(fn ($i) => ['id' => (string) $i->product_id, 'quantity' => (int) $i->quantity, 'item_price' => (float) $i->price])->values()->all(),
            'num_items'    => (int) $order->items->sum('quantity'),
            'order_id'     => $order->order_number,
        ], $request, [
            'em' => $order->email,
            'ph' => \App\Support\PkPhone::whatsapp((string) $order->phone),
            'fn' => $order->first_name,
            'ln' => $order->last_name,
            'ct' => $order->city,
        ]);
    }

    private static function userData(Request $request, array $user): array
    {
        $hash = fn ($v) => hash('sha256', $v);
        $norm = [
            'em' => fn ($v) => strtolower(trim($v)),
            'ph' => fn ($v) => preg_replace('/\D/', '', $v),
            'fn' => fn ($v) => strtolower(trim($v)),
            'ln' => fn ($v) => strtolower(trim($v)),
            'ct' => fn ($v) => preg_replace('/[^a-z]/', '', strtolower($v)),
        ];

        $data = [
            'client_ip_address' => $request->ip(),
            'client_user_agent' => (string) $request->userAgent(),
            'country'           => [$hash('pk')],
        ];
        foreach ($norm as $key => $fn) {
            $v = isset($user[$key]) ? $fn((string) $user[$key]) : '';
            if ($v !== '') {
                $data[$key] = [$hash($v)];
            }
        }
        // Browser ids set by the Pixel: link this server event to the same person.
        if ($fbp = $request->cookie('_fbp')) {
            $data['fbp'] = $fbp;
        }
        if ($fbc = $request->cookie('_fbc')) {
            $data['fbc'] = $fbc;
        }

        return $data;
    }
}
