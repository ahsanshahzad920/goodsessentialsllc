<?php

namespace App\Payments;

final class PaymentResult
{
    public const PAID    = 'paid';
    public const FAILED  = 'failed';
    public const PENDING = 'pending';

    public function __construct(
        public readonly string $status,
        public readonly ?string $reference = null,
    ) {}

    public function isPaid(): bool
    {
        return $this->status === self::PAID;
    }
}
