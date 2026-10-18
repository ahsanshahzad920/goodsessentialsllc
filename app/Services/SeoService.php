<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;

class SeoService
{
    public static function set(string $title, string $description, ?string $image = null, ?string $canonical = null, bool $index = true): void
    {
        $title = self::truncate($title, 60);
        $description = self::truncate($description, 160);
        $canonical = $canonical ?? url()->current();

        SEOMeta::setTitle($title)
            ->setDescription($description)
            ->setCanonical($canonical);

        SEOMeta::addMeta('robots', $index ? 'index, follow' : 'noindex, follow', 'name');

        OpenGraph::setTitle($title)
            ->setDescription($description)
            ->setUrl($canonical)
            ->setType('website')
            ->setSiteName(config('app.name'));

        TwitterCard::setTitle($title)->setDescription($description);

        if ($image) {
            OpenGraph::addImage($image);
            TwitterCard::setImage($image);
            TwitterCard::setType('summary_large_image');
        } else {
            TwitterCard::setType('summary');
        }
    }

    public static function forProduct(Product $product): void
    {
        $title = $product->meta_title ?: self::productTitleFallback($product);
        $description = $product->meta_description ?: self::productDescriptionFallback($product);
        $image = self::productImage($product);

        self::set($title, $description, $image);
        OpenGraph::setType('product');
    }

    public static function forCategory(Category $category): void
    {
        $title = $category->meta_title ?: "{$category->name} Price in Pakistan | Buy Online \xe2\x80\x93 " . config('app.name');
        $description = $category->meta_description ?: "Shop {$category->name} online in Pakistan with Cash on Delivery, fast delivery and easy returns at " . config('app.name') . '.';
        $image = $category->og_image ? asset('storage/' . $category->og_image) : null;

        self::set($title, $description, $image);
    }

    private static function productTitleFallback(Product $product): string
    {
        $price = $product->price ? ' \xe2\x80\x93 Rs. ' . number_format((float) $product->price) : '';
        return self::truncate($product->product_title . $price . ' | Price in Pakistan', 60);
    }

    private static function productDescriptionFallback(Product $product): string
    {
        $categoryName = $product->category->name ?? config('app.name');
        $base = $product->product_description
            ? strip_tags($product->product_description)
            : "Shop {$product->product_title} from our {$categoryName} range at " . config('app.name') . '.';

        return self::truncate($base . ' Cash on Delivery available across Pakistan.', 160);
    }

    private static function productImage(Product $product): ?string
    {
        if ($product->og_image) {
            return asset('storage/' . $product->og_image);
        }

        if ($product->product_image) {
            return asset('storage/' . $product->product_image);
        }

        return null;
    }

    public static function truncate(string $text, int $limit): string
    {
        $text = trim(preg_replace('/\s+/', ' ', $text) ?? $text);

        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $limit - 1)) . "\xe2\x80\xa6";
    }
}
