# Storefront performance

PageSpeed Insights, mobile, 24 Nov 2026:

| Page | Perf | A11y | BP | SEO | LCP | TBT | CLS |
|---|---|---|---|---|---|---|---|
| `/` | 100 | 100 | 100 | 100 | 1.1 s | 0 ms | 0.003 |
| `/shop` | 99 | 100 | 100 | 100 | 1.6 s | 0 ms | 0 |
| `/gift-box` | 100 | 100 | 100 | 100 | 1.5 s | 0 ms | 0 |

Before this work the scores were 84 / 90 / 79, with LCP between 3.0 and 5.2 s. Most visitors come from Meta/TikTok ads on mid-range phones, so these numbers matter for sales. Re-test the three URLs on <https://pagespeed.web.dev> (Mobile) after any change to the layout, the header or the homepage.

## What is in place

### Images: `App\Support\WebpImage`
- Every upload in `products/`, `categories/` and `reviews/` gets WebP files next to it:
  - `abc.jpg.webp`: full size, at most 1200 px.
  - `abc.jpg.w240.webp`, `.w480.webp`, `.w800.webp`: quality 70, never upscaled.
- The admin upload controllers create these files automatically. To backfill or rebuild them, run `php artisan images:webp` (add `--force` to rebuild existing ones). **Run it after every deploy that touches images.**
- `<x-picture :path sizes priority>` renders a plain `<img>`:
  - `src` is WebP (the 480 px file), with a WebP `srcset` and your `sizes`.
  - It is lazy by default.
  - With `priority` it is eager and `fetchpriority="high"`.
- `WebpImage::fit($path, $minWidth)` returns one URL for fixed-size thumbnails such as the cart drawer, search results and the mega menu.

### LCP preload: `<x-preload-image>`
- It writes `<link rel="preload" as="image" imagesrcset imagesizes fetchpriority="high">` into the `preload` stack, which is the first thing in `<head>`.
- It reads the same `WebpImage::responsive()` data as `<x-picture>`, so the preload and the `<img>` always ask for the same file and nothing is downloaded twice.
- Used for:
  - the first hero slide;
  - the first shop card;
  - the first gift-box item;
  - the first product-gallery image.

### HTML gzip: `App\Http\Middleware\CompressHtml`
The host's LiteSpeed compresses HTML with a weak brotli level: the homepage was 92 KB as brotli and is 59 KB as gzip. The page only starts loading the images it finds once the HTML has arrived, so this middleware gzips `text/html` responses itself. LiteSpeed passes them through unchanged.

### CSS and fonts (`layouts/guest.blade.php`)
- The built stylesheet (about 17 KB compressed, with no `url()`s) is inlined in a `<style>` tag. If the build file can't be read, the layout falls back to a normal `<link>`.
- Nunito and Fredoka are self-hosted Latin variable fonts in `public/fonts`, under the SIL OFL licence, with `font-display: swap`. Google Fonts is not used.
- The fonts are **not preloaded** on purpose. Preloading them took bandwidth away from the LCP image.

### Third-party and decorative JavaScript
- **Meta Pixel** (`partials/meta-pixel.blade.php`):
  - The `fbq` stub is installed at once, so PageView and every `fbqTrack()` call are queued, with event IDs, for Conversions API dedup.
  - `fbevents.js` itself loads on the first tap, scroll or key press, or 3.5 s after `load`, whichever comes first.
  - It loads straight away on `/checkout` and `/order/success`.
- **Three.js hero scene** (`resources/js/hero3d.js`): loads only after the first user interaction.
- **Motion:**
  - Hero autoplay and the announcement-bar rotation start only after the first scroll, tap or key press.
  - Scroll-reveal shows whatever is already on screen at load without a fade.
- Load-time scripts read layout first and then write, or run inside `requestAnimationFrame`, so there are no forced reflows.
- The homepage WhatsApp button is hidden on phones from the server side, with no JS needed at first paint.

### Caching (`public/.htaccess`)
- Hashed Vite files (`/build/assets/*-xxxxxxxx.css|js`) and `.woff2` fonts get `Cache-Control: public, max-age=31536000, immutable`.
- `storage/` images are cached for one year by the server's existing `mod_expires` rules.
- The Vite modulepreload polyfill is turned off in `vite.config.js`.

## Rules to keep these numbers

1. **Every storefront product image goes through `<x-picture>` or `WebpImage::fit()`.** Never output `asset('storage/...')` straight into an `<img>`. Always give `<x-picture>` a `sizes` value that matches how wide the image is actually shown.
2. **The page's LCP image** (the largest thing on screen at load) uses `<x-picture :priority="true">` **and** `<x-preload-image>`, with the same `path` and `sizes`. Only one image per page gets `priority`. Never lazy-load it, never inject it with JavaScript, and never set it as a CSS background.
3. **Don't add render-blocking resources to `<head>`.** That means no external stylesheets, no Google Fonts and no synchronous `<script src>`. New first-party CSS goes in `resources/css/app.css`, which is inlined. Keep that file free of `url()`s; if one is unavoidable, move the stylesheet back to `@vite` on purpose and re-test.
4. **Keep third-party scripts deferred.** New pixels, chat widgets or analytics load the way the Meta Pixel does: install a queueing stub, then fetch the script on first interaction or a few seconds after `load`. Don't replace the Meta Pixel snippet with Meta's default one.
5. **Don't preload fonts**, and don't add more font families or weights. Both fonts are variable files that already cover every weight.
6. **No motion during the initial load.** Carousels, rotating banners, counters and 3D start after the first interaction. Animate only `transform` and `opacity`. In scripts that run at load, read layout before you write to it.
7. **Keep the HTML lean.** Everything in the page markup delays every image. Desktop-only or hidden UI should stay small; consider loading it on demand.
8. **After a deploy:**
   - Run `php artisan images:webp` if any images changed.
   - Run `optimize:clear`, `config:cache`, `route:cache` and `view:cache`.
   - Upload the locally built `public/build`, because the server has no Node.
   - Re-test the three URLs on PageSpeed (Mobile).
