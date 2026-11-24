{{-- Body of the slide-in cart, fetched from GET /cart/drawer (CartController::drawer). --}}
@php use App\Support\WebpImage; @endphp

<div data-drawer-count="{{ $count }}" class="flex min-h-full flex-col">
    @if ($lines->isEmpty())
        <div class="m-auto py-10 text-center">
            <span class="mx-auto grid size-16 place-items-center rounded-full bg-lavender-100 text-brand-600">
                <x-shop.icon name="cart" class="size-7" />
            </span>
            <p class="mt-4 font-display text-lg font-semibold">Your cart is empty</p>
            <p class="mt-1 text-sm text-muted">Add something lovely for your little one.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-5">Start shopping</a>
        </div>
    @else
        {{-- Free-delivery progress (only when the owner has set a threshold) --}}
        @if ($threshold)
            <div class="mb-4 rounded-2xl bg-mint-100 p-3">
                <p class="text-sm font-bold text-ink">
                    @if ($remaining)
                        Add <span class="text-mint-600">{{ money($remaining) }}</span> more for <strong>FREE delivery</strong> 🎉
                    @else
                        You've unlocked <strong class="text-mint-600">FREE delivery</strong> 🎉
                    @endif
                </p>
                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-white" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $progress }}" aria-label="Progress to free delivery">
                    <div class="h-full origin-left rounded-full bg-mint-600 transition-transform duration-500 ease-soft" style="transform: scaleX({{ $progress / 100 }})"></div>
                </div>
            </div>
        @endif

        <ul class="divide-y divide-line">
            @foreach ($lines as $line)
                @php $img = $line['product_image'] ? (WebpImage::fit($line['product_image'], 240)) : asset('images/product-placeholder.svg'); @endphp
                <li class="flex gap-3 py-3" data-line="{{ $line['key'] }}">
                    <a href="{{ route('productDetails', $line['product_slug'] ?? '') }}" class="shrink-0">
                        <img src="{{ $img }}" alt="" width="72" height="72" class="size-18 rounded-xl bg-frame object-contain p-1">
                    </a>
                    <div class="min-w-0 flex-1">
                        <p class="line-clamp-2 text-sm font-bold leading-snug text-ink">{{ $line['product_title'] }}</p>
                        @if (! empty($line['color_name']))
                            <p class="mt-0.5 text-xs text-muted">Colour: {{ $line['color_name'] }}</p>
                        @endif
                        <div class="mt-2 flex items-center justify-between gap-2">
                            <div class="inline-flex items-center rounded-full border border-line">
                                <button type="button" data-drawer-action="decrement" data-key="{{ $line['key'] }}" class="grid size-9 place-items-center text-ink disabled:opacity-40" aria-label="Decrease quantity" @disabled($line['quantity'] <= 1)>&minus;</button>
                                <span class="w-7 text-center text-sm font-bold" aria-live="polite">{{ $line['quantity'] }}</span>
                                <button type="button" data-drawer-action="increment" data-key="{{ $line['key'] }}" class="grid size-9 place-items-center text-ink" aria-label="Increase quantity">&plus;</button>
                            </div>
                            <span class="text-sm font-extrabold text-ink">{{ money($line['quantity'] * $line['product_price']) }}</span>
                        </div>
                        <button type="button" data-drawer-action="remove" data-key="{{ $line['key'] }}" class="mt-1 inline-flex min-h-8 items-center gap-1 text-xs font-bold text-muted hover:text-coral-700">
                            <x-shop.icon name="trash" class="size-3.5" /> Remove
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- One-tap add-ons --}}
        @if ($addons->isNotEmpty())
            <div class="mt-4">
                <p class="mb-2 text-xs font-extrabold uppercase tracking-wider text-muted">Add a little extra</p>
                <ul class="space-y-2">
                    @foreach ($addons as $p)
                        @php
                            $price = (float) str_replace(',', '', (string) $p->price);
                            $pay = (float) calculateDiscountPrice($price, (float) $p->dis_price) ?: $price;
                            $c = $p->colors ?? [];
                        @endphp
                        <li class="flex items-center gap-3 rounded-2xl bg-frame p-2">
                            <img src="{{ WebpImage::fit($p->product_image, 240) }}" alt="" width="48" height="48" loading="lazy" class="size-12 shrink-0 rounded-xl bg-white object-contain p-0.5">
                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-1 text-xs font-bold text-ink">{{ $p->product_title }}</p>
                                <p class="text-xs font-extrabold text-coral-600">{{ money($pay) }}</p>
                            </div>
                            <form method="POST" action="{{ route('cart.store') }}" data-ajax-cart data-price="{{ $pay }}" data-sku="{{ $p->sku ?: $p->id }}" data-name="{{ $p->product_title }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id }}">
                                <input type="hidden" name="quantity" value="1">
                                @if (count($c) === 1) <input type="hidden" name="color" value="{{ $c[0]['hex'] }}"> @endif
                                <button type="submit" class="btn btn-soft btn-sm" aria-label="Add {{ $p->product_title }}"><x-shop.icon name="plus" class="size-4" /> Add</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Totals + checkout, pinned to the bottom of the drawer --}}
        <div class="sticky bottom-0 -mx-5 mt-auto border-t border-line bg-white px-5 pb-[max(1rem,env(safe-area-inset-bottom))] pt-4">
            <div class="flex items-center justify-between text-sm">
                <span class="font-bold text-muted">Subtotal</span>
                <span class="text-lg font-extrabold text-ink">{{ money($subtotal) }}</span>
            </div>
            <p class="mt-0.5 text-xs text-muted">Delivery calculated at checkout{{ $threshold && ! $remaining ? ' — yours is FREE' : '' }}.</p>
            <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg mt-3 w-full">
                <x-shop.icon name="lock" class="size-5" /> Checkout — Cash on Delivery
            </a>
            <a href="{{ route('cart') }}" class="mt-2 block text-center text-xs font-bold text-brand-600 hover:underline">View full cart</a>
        </div>
    @endif
</div>
