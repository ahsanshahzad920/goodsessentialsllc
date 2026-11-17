{{-- Kept for existing call sites; the design-system card is <x-product-card>. --}}
@props(['product', 'wishlisted' => false, 'priority' => false])
<x-product-card :product="$product" :wishlisted="$wishlisted" :priority="$priority" {{ $attributes }} />
