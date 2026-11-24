<?php

namespace App\Console\Commands;

use App\Support\WebpImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateWebpImages extends Command
{
    protected $signature = 'images:webp {--force : Regenerate existing WebP files}';

    protected $description = 'Create WebP siblings for product and category images on the public disk';

    public function handle(): int
    {
        if (! function_exists('imagewebp')) {
            $this->error('GD on this PHP build has no WebP support; nothing to do.');
            return self::FAILURE;
        }

        $made = $skipped = 0;
        foreach (['products', 'categories', 'reviews'] as $dir) {
            foreach (Storage::disk('public')->allFiles($dir) as $path) {
                // Skip generated files (abc.jpg.webp, abc.jpg.w480.webp); real .webp uploads still get width variants.
                if (preg_match('/\.(jpe?g|png|webp)(\.w\d+)?\.webp$/i', $path)) {
                    continue;
                }
                WebpImage::generate($path, (bool) $this->option('force')) ? $made++ : $skipped++;
            }
        }

        $this->info("WebP created: {$made}, skipped: {$skipped}");

        return self::SUCCESS;
    }
}
