<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Gzip HTML pages in PHP.
 *
 * The host's LiteSpeed compresses HTML with a very low brotli level: the
 * homepage goes out as 92 KB br, but only 59 KB as gzip. HTML size directly
 * delays every resource the page discovers (LCP image included), so we send
 * gzip ourselves. LiteSpeed passes an already-encoded response through untouched.
 */
class CompressHtml
{
    private const MIN_BYTES = 1024;
    private const LEVEL = 6;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof StreamedResponse || $response instanceof BinaryFileResponse
            || $response->headers->has('Content-Encoding')
            || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')
            || ! preg_match('/\bgzip\b/i', (string) $request->header('Accept-Encoding'))
            || ! function_exists('gzencode')) {
            return $response;
        }

        $content = $response->getContent();
        if ($content === false || strlen($content) < self::MIN_BYTES) {
            return $response;
        }

        $gz = gzencode($content, self::LEVEL);
        if ($gz === false) {
            return $response;
        }

        $response->setContent($gz);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Content-Length', (string) strlen($gz));
        $response->setVary(array_unique(array_merge($response->getVary(), ['Accept-Encoding'])));

        return $response;
    }
}
