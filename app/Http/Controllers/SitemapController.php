<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        $urls[] = $this->url(url('/'), now(), '1.0');
        $urls[] = $this->url(route('about'), now()->subMonth(), '0.6');
        $urls[] = $this->url(route('contacts.index'), now()->subMonth(), '0.6');
        $urls[] = $this->url(route('shop'), now(), '0.8');
        $urls[] = $this->url(route('privacy-policy'), now()->subMonth(), '0.3');
        $urls[] = $this->url(route('return-refund-policy'), now()->subMonth(), '0.3');
        $urls[] = $this->url(route('shipping-policy'), now()->subMonth(), '0.3');
        $urls[] = $this->url(route('terms-and-conditions'), now()->subMonth(), '0.3');

        foreach (Category::all() as $category) {
            $urls[] = $this->url(route('shop.category', $category->slug), $category->updated_at, '0.8');
        }

        foreach (Product::all() as $product) {
            $urls[] = $this->url(route('productDetails', $product->slug), $product->updated_at, '0.9');
        }

        if (class_exists(\App\Models\BlogPost::class)) {
            foreach (\App\Models\BlogPost::where('status', 'published')->get() as $post) {
                $urls[] = $this->url(route('blog.show', $post->slug), $post->updated_at, '0.7');
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"
            . implode('', $urls)
            . '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    private function url(string $loc, $lastmod, string $priority): string
    {
        $lastmodStr = $lastmod ? $lastmod->toAtomString() : now()->toAtomString();

        return "  <url>\n"
            . '    <loc>' . e($loc) . "</loc>\n"
            . '    <lastmod>' . $lastmodStr . "</lastmod>\n"
            . '    <priority>' . $priority . "</priority>\n"
            . "  </url>\n";
    }
}
