{{--
    <x-countdown :end="$deal->ends_at" label="Deal ends in" />

    `end` must be a REAL end time (Carbon / DateTime / parseable string from
    admin data). Renders nothing when it is missing or already past, and
    removes itself (or the nearest [data-countdown-scope]) the moment it
    expires — so an expired deal is never shown with a 00:00:00 timer.
--}}
@props(['end' => null, 'label' => 'Ends in'])

@php
    $endAt = $end ? \Illuminate\Support\Carbon::parse($end) : null;
@endphp

@if ($endAt && $endAt->isFuture())
    <div {{ $attributes->class('inline-flex items-center gap-2') }}
         data-countdown="{{ $endAt->toIso8601String() }}"
         role="timer" aria-label="{{ $label }} {{ $endAt->diffForHumans(['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}">
        <span class="text-xs font-bold text-muted">{{ $label }}</span>
        <span class="flex gap-1 font-display text-sm font-semibold tabular-nums" aria-hidden="true">
            @foreach (['d' => 'd', 'h' => 'h', 'm' => 'm', 's' => 's'] as $unit => $suffix)
                <span class="grid min-w-9 place-items-center rounded-lg bg-ink px-1.5 py-1 text-white" @if ($unit === 'd') data-cd-days-wrap @endif>
                    <span><span data-cd="{{ $unit }}">--</span>{{ $suffix }}</span>
                </span>
            @endforeach
        </span>
    </div>

    @once
            <script>
                (function () {
                    function pad(n) { return String(n).padStart(2, '0'); }
                    function tick() {
                        document.querySelectorAll('[data-countdown]').forEach(function (el) {
                            var left = Math.floor((new Date(el.dataset.countdown) - Date.now()) / 1000);
                            if (left <= 0) {
                                (el.closest('[data-countdown-scope]') || el).remove();
                                return;
                            }
                            var d = Math.floor(left / 86400), h = Math.floor(left % 86400 / 3600),
                                m = Math.floor(left % 3600 / 60), s = left % 60;
                            el.querySelector('[data-cd="d"]').textContent = d;
                            el.querySelector('[data-cd-days-wrap]').hidden = d === 0;
                            el.querySelector('[data-cd="h"]').textContent = pad(h);
                            el.querySelector('[data-cd="m"]').textContent = pad(m);
                            el.querySelector('[data-cd="s"]').textContent = pad(s);
                        });
                    }
                    tick();
                    setInterval(tick, 1000);
                })();
            </script>
    @endonce
@endif
