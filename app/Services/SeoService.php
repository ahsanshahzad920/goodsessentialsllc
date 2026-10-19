<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Spatie\SchemaOrg\Schema;

class SeoService
{
    /** @var array<int, \Spatie\SchemaOrg\BaseType> */
    protected static array $schemas = [];

    public static function set(string $title, string $description, ?string $image = null, ?string $canonical = null, bool $index = true): void
    {
        $title = self::truncate($title, 60);
        $description = self::truncate($description, 160);
        $canonical = $canonical ?? url()->current();

        SEOMeta::setTitle($title, false)
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

        if ($index) {
            self::addSchema(self::organization());
            self::addSchema(self::website());
        }
    }

    public static function forProduct(Product $product): void
    {
        $title = $product->meta_title ?: self::productTitleFallback($product);
        $description = $product->meta_description ?: self::productDescriptionFallback($product);
        $image = self::productImage($product);

        self::set($title, $description, $image);
        OpenGraph::setType('product');

        self::addSchema(self::productSchema($product));
        self::addSchema(self::breadcrumbs([
            ['name' => 'Home', 'url' => url('/')],
            ...($product->category ? [['name' => $product->category->name, 'url' => route('shop.category', $product->category->slug)]] : []),
            ['name' => $product->product_title, 'url' => route('productDetails', $product->slug)],
        ]));
    }

    public static function forCategory(Category $category): void
    {
        $title = $category->meta_title ?: "{$category->name} Wholesale Price in Pakistan – " . config('app.name');
        $description = $category->meta_description ?: "Shop {$category->name} online in Pakistan at wholesale prices without compromising on quality. Cash on Delivery, fast delivery and easy returns at " . config('app.name') . '.';
        $image = $category->og_image ? asset('storage/' . $category->og_image) : null;

        self::set($title, $description, $image);

        self::addSchema(self::breadcrumbs([
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $category->name, 'url' => route('shop.category', $category->slug)],
        ]));
    }

    /** @param \Illuminate\Support\Collection<int, Product> $products */
    public static function itemList($products): void
    {
        $items = $products->values()->map(
            fn (Product $product, int $i) => Schema::listItem()
                ->position($i + 1)
                ->url(route('productDetails', $product->slug))
        )->all();

        if ($items) {
            self::addSchema(Schema::itemList()->itemListElement($items));
        }
    }

    /** @param array<string, string> $questionsAndAnswers question text => answer text */
    public static function faq(array $questionsAndAnswers): void
    {
        if (! $questionsAndAnswers) {
            return;
        }

        $entities = [];
        foreach ($questionsAndAnswers as $question => $answer) {
            $entities[] = Schema::question()
                ->name($question)
                ->acceptedAnswer(Schema::answer()->text($answer));
        }

        self::addSchema(Schema::fAQPage()->mainEntity($entities));
    }

    public static function addSchema($schema): void
    {
        static::$schemas[] = $schema;
    }

    public static function renderSchemas(): string
    {
        $html = '';
        foreach (static::$schemas as $schema) {
            $html .= $schema->toScript() . "\n";
        }

        static::$schemas = [];

        return $html;
    }

    protected static function organization(): \Spatie\SchemaOrg\Organization
    {
        $org = Schema::organization()
            ->name(config('app.name'))
            ->url(config('app.url'));

        if ($logo = config('shop.logo_image')) {
            $org->logo(asset($logo));
        }

        $sameAs = array_values(array_filter(config('shop.social', [])));
        if ($sameAs) {
            $org->sameAs($sameAs);
        }

        $contact = config('shop.contact', []);
        if (! empty($contact['phone']) || ! empty($contact['email'])) {
            $contactPoint = Schema::contactPoint()
                ->contactType('customer service')
                ->areaServed('PK')
                ->availableLanguage(['en', 'ur']);

            if (! empty($contact['phone'])) {
                $contactPoint->telephone($contact['phone']);
            }
            if (! empty($contact['email'])) {
                $contactPoint->email($contact['email']);
            }

            $org->contactPoint($contactPoint);
        }

        return $org;
    }

    protected static function website(): \Spatie\SchemaOrg\WebSite
    {
        return Schema::webSite()
            ->name(config('app.name'))
            ->url(config('app.url'))
            ->potentialAction(
                Schema::searchAction()
                    ->target(
                        Schema::entryPoint()->urlTemplate(url('/shop') . '?q={search_term_string}')
                    )
                    ->setProperty('query-input', 'required name=search_term_string')
            );
    }

    /** @param array<int, array{name: string, url: string}> $crumbs */
    protected static function breadcrumbs(array $crumbs): \Spatie\SchemaOrg\BreadcrumbList
    {
        $items = [];
        foreach (array_values($crumbs) as $i => $crumb) {
            $items[] = Schema::listItem()->position($i + 1)->name($crumb['name'])->item($crumb['url']);
        }

        return Schema::breadcrumbList()->itemListElement($items);
    }

    protected static function productSchema(Product $product): \Spatie\SchemaOrg\Product
    {
        $images = $product->images->isNotEmpty()
            ? $product->images->map(fn ($image) => $image->url)->all()
            : array_filter([self::productImage($product)]);

        $price = (float) str_replace(',', '', (string) ($product->dis_price ?: $product->price ?: 0));

        $offer = Schema::offer()
            ->price($price)
            ->priceCurrency('PKR')
            ->availability('https://schema.org/InStock')
            ->itemCondition('https://schema.org/NewCondition')
            ->url(route('productDetails', $product->slug))
            ->shippingDetails(
                Schema::offerShippingDetails()
                    ->shippingDestination(Schema::definedRegion()->addressCountry('PK'))
                    ->shippingRate(Schema::monetaryAmount()->currency('PKR')->value(200))
            )
            ->hasMerchantReturnPolicy(
                Schema::merchantReturnPolicy()
                    ->applicableCountry('PK')
                    ->returnPolicyCategory('https://schema.org/MerchantReturnFiniteReturnWindow')
                    ->merchantReturnDays(14)
                    ->returnMethod('https://schema.org/ReturnByMail')
            );

        $schema = Schema::product()
            ->name($product->product_title)
            ->description(strip_tags($product->product_description ?: ''))
            ->sku($product->sku ?: (string) $product->id)
            ->offers($offer);

        if ($images) {
            $schema->image(array_values($images));
        }
        if ($product->brand) {
            $schema->brand(Schema::brand()->name($product->brand));
        }
        if ($product->color) {
            $schema->color($product->color);
        }
        if ($product->material) {
            $schema->material($product->material);
        }

        if ($product->suggested_min_age_months !== null || $product->suggested_max_age_months !== null) {
            $audience = Schema::peopleAudience();
            if ($product->suggested_min_age_months !== null) {
                $audience->suggestedMinAge(round($product->suggested_min_age_months / 12, 2));
            }
            if ($product->suggested_max_age_months !== null) {
                $audience->suggestedMaxAge(round($product->suggested_max_age_months / 12, 2));
            }
            $schema->audience($audience);
        }

        return $schema;
    }

    private static function productTitleFallback(Product $product): string
    {
        $price = $product->price ? ' – Rs. ' . number_format((float) $product->price) : '';
        return self::truncate($product->product_title . $price . ' | Wholesale Price in Pakistan', 60);
    }

    private static function productDescriptionFallback(Product $product): string
    {
        $categoryName = $product->category->name ?? config('app.name');
        $base = $product->product_description
            ? strip_tags($product->product_description)
            : "Shop {$product->product_title} from our {$categoryName} range at " . config('app.name') . ' at wholesale rates, without compromising on quality.';

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

        return rtrim(mb_substr($text, 0, $limit - 1)) . '…';
    }
}
