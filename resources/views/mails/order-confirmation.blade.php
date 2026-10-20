<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body style="margin:0;padding:0;background:#f5f6fa;font-family:Arial,sans-serif;color:#333;">

<div style="max-width:650px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;">

    <div style="background:#4c49e3;padding:28px 20px;text-align:center;color:#fff;">
        <h1 style="margin:0;font-size:28px;">Good Essentials</h1>
        <p style="margin:8px 0 0;">For happy little ones</p>
    </div>

    <div style="padding:30px 25px;">
        <h2 style="margin-top:0;">Thank you for your order!</h2>

        <p style="font-size:15px;line-height:1.7;">
            Hello {{ $order->first_name }},
            thank you for shopping with Good Essentials.
            Here are your order details.
        </p>

        <table style="width:100%;border-collapse:collapse;margin:20px 0;">
            <tr>
                <td style="padding:10px;border:1px solid #eee;">Order Number</td>
                <td style="padding:10px;border:1px solid #eee;">
                    <strong>{{ $order->order_number }}</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:10px;border:1px solid #eee;">Order Date</td>
                <td style="padding:10px;border:1px solid #eee;">
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </td>
            </tr>
            <tr>
                <td style="padding:10px;border:1px solid #eee;">Order Status</td>
                <td style="padding:10px;border:1px solid #eee;">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </td>
            </tr>
            <tr>
                <td style="padding:10px;border:1px solid #eee;">Payment Method</td>
                <td style="padding:10px;border:1px solid #eee;">
                    {{ strtoupper($order->payment_method) }}
                </td>
            </tr>
            <tr>
                <td style="padding:10px;border:1px solid #eee;">Payment Status</td>
                <td style="padding:10px;border:1px solid #eee;">
                    {{ ucfirst($order->payment_status) }}
                </td>
            </tr>
        </table>

        <h3 style="margin-top:28px;">Items Ordered</h3>

        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            <thead>
                <tr style="background:#f5f6fa;">
                    <th style="padding:10px;border:1px solid #eee;text-align:left;">Product</th>
                    <th style="padding:10px;border:1px solid #eee;">Qty</th>
                    <th style="padding:10px;border:1px solid #eee;text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td style="padding:10px;border:1px solid #eee;">
                            <strong>{{ $item->product_title }}</strong>

                            @if ($item->color_name)
                                <br>
                                <span style="color:#666;">
                                    Color: {{ $item->color_name }}
                                </span>
                            @endif

                            <br>
                            <span style="color:#666;">
                                Unit price: PKR {{ number_format((float) $item->price, 2) }}
                            </span>
                        </td>
                        <td style="padding:10px;border:1px solid #eee;text-align:center;">
                            {{ $item->quantity }}
                        </td>
                        <td style="padding:10px;border:1px solid #eee;text-align:right;white-space:nowrap;">
                            PKR {{ number_format((float) $item->total, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table style="width:100%;border-collapse:collapse;margin-top:20px;">
            <tr>
                <td style="padding:8px;text-align:right;">Subtotal:</td>
                <td style="padding:8px;text-align:right;">
                    PKR {{ number_format((float) $order->subtotal, 2) }}
                </td>
            </tr>
            <tr>
                <td style="padding:8px;text-align:right;">Shipping:</td>
                <td style="padding:8px;text-align:right;">
                    PKR {{ number_format((float) $order->shipping, 2) }}
                </td>
            </tr>
            <tr>
                <td style="padding:12px 8px;text-align:right;font-size:18px;">
                    <strong>Grand Total:</strong>
                </td>
                <td style="padding:12px 8px;text-align:right;font-size:18px;color:#4c49e3;">
                    <strong>
                        PKR {{ number_format((float) $order->total, 2) }}
                    </strong>
                </td>
            </tr>
        </table>

        <h3 style="margin-top:28px;">Delivery Details</h3>

        <p style="font-size:14px;line-height:1.8;">
            <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
            {{ $order->address }}<br>
            {{ $order->city }}, {{ $order->zip_code }}<br>
            Phone: {{ $order->phone }}<br>
            Email: {{ $order->email }}
        </p>

        @if ($order->notes)
            <p style="font-size:14px;line-height:1.7;">
                <strong>Customer Notes:</strong><br>
                {{ $order->notes }}
            </p>
        @endif

        <p style="font-size:14px;line-height:1.7;color:#666;margin-top:25px;">
            If you have any questions about your order, contact us at
            <a href="mailto:info@goodsessentialsllc.com">
                info@goodsessentialsllc.com
            </a>.
        </p>

        <p style="line-height:1.7;">
            Thank you,<br>
            <strong>Good Essentials Team</strong>
        </p>
    </div>

    <div style="background:#f0f0f5;text-align:center;padding:18px;font-size:12px;color:#777;">
        &copy; {{ date('Y') }} Good Essentials. All rights reserved.
    </div>

</div>
</body>
</html>
