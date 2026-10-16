<?php

namespace App\Payments;

use App\Payments\Contracts\PaymentGateway;

final class PaymentManager
{
    /** Driver class for the configured gateway, or null. */
    public static function gatewayClass(): ?string
    {
        $key   = config('payments.gateway');
        $class = $key ? config("payments.gateways.{$key}") : null;

        return ($class && class_exists($class)) ? $class : null;
    }

    public static function cardAvailable(): bool
    {
        $class = self::gatewayClass();

        return $class !== null
            && (bool) config('payments.methods.card.enabled', true)
            && $class::isConfigured();
    }

    public static function gateway(): PaymentGateway
    {
        $class = self::gatewayClass();

        abort_unless($class !== null && $class::isConfigured(), 404);

        return app($class);
    }

    /** Methods the customer can pick right now: ['cod' => [...], 'card' => [...]] */
    public static function methods(): array
    {
        $methods = [];

        foreach (config('payments.methods', []) as $key => $method) {
            if (! ($method['enabled'] ?? true)) {
                continue;
            }

            if ($key === 'card' && ! self::cardAvailable()) {
                continue;
            }

            $methods[$key] = $method;
        }

        return $methods;
    }
}
