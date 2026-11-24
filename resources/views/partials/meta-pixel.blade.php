@if ($pixelId = config('tracking.meta_pixel_id'))
<!-- Meta Pixel Code (deferred) -->
{{-- The standard fbq stub is installed at once, so PageView and every
     fbqTrack() call are queued from the first moment. Only fetching
     fbevents.js (~100 KB of script) waits: it loads on the shopper's first
     tap/scroll/key, or 3.5s after the page "load" event, whichever comes
     first (immediately on checkout and the order-success page) — off the critical path for LCP and main-thread time. fbevents
     replays the queue when it arrives (same event IDs, so CAPI dedup holds). --}}
<script>
!function(f,b,v){if(f.fbq)return;var n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];
var done=!1,ev=['pointerdown','touchstart','scroll','keydown'];
function load(){if(done)return;done=!0;ev.forEach(function(e){f.removeEventListener(e,load)});
var t=b.createElement('script');t.async=!0;t.src=v;b.head.appendChild(t)}
ev.forEach(function(e){f.addEventListener(e,load,{passive:!0})});
@if (request()->is('checkout*', 'order/success*'))
load(); {{-- checkout / Purchase pages: conversion events matter more than speed --}}
@else
if(b.readyState==='complete')setTimeout(load,3500);
else f.addEventListener('load',function(){setTimeout(load,3500)})
@endif
}(window,document,'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', @js($pixelId));
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1"/></noscript>
<!-- End Meta Pixel Code -->
@endif
