<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * WebP siblings and responsive widths for uploaded images on the public disk.
 *
 * "products/abc.jpg" gets:
 *   products/abc.jpg.webp        full size (longest edge <= 1200px)
 *   products/abc.jpg.w240.webp   240px wide  (cards on small phones, thumbs)
 *   products/abc.jpg.w480.webp   480px wide  (cards on 2x/3x phones, hero)
 *   products/abc.jpg.w800.webp   800px wide  (desktop hero, product gallery)
 * The suffix form can never collide with another upload. Uploads that are
 * already WebP only get the width variants. Views use srcset() through
 * <x-picture>; when nothing was generated they fall back to the original.
 */
class WebpImage
{
    public const MAX_EDGE = 1200;
    public const QUALITY = 80;
    public const WIDTHS = [240, 480, 800];
    // 70 keeps the 480px LCP-sized files around 15 KB with no visible loss on product shots.
    public const VARIANT_QUALITY = 70;

    public static function siblingPath(string $path): string
    {
        return $path . '.webp';
    }

    public static function variantPath(string $path, int $width): string
    {
        return $path . '.w' . $width . '.webp';
    }

    /** Public URL of the WebP sibling, or null when there is none / the source is already WebP. */
    public static function url(?string $path): ?string
    {
        if (! $path || self::isWebp($path)) {
            return null;
        }
        $sibling = self::siblingPath($path);

        return Storage::disk('public')->exists($sibling) ? asset('storage/' . $sibling) : null;
    }

    /**
     * WebP candidates as [url => width], smallest first: the width variants
     * plus the largest WebP available (sibling, or the upload itself when it
     * is already WebP). Cached per path for a day; generate()/delete() reset it.
     *
     * @return array<string, int>
     */
    public static function candidates(?string $path): array
    {
        if (! $path) {
            return [];
        }

        return Cache::remember(self::cacheKey($path), now()->addDay(), function () use ($path) {
            $disk = Storage::disk('public');
            $out = [];
            foreach (self::WIDTHS as $w) {
                if ($disk->exists(self::variantPath($path, $w))) {
                    $out[asset('storage/' . self::variantPath($path, $w))] = $w;
                }
            }
            $full = self::isWebp($path) ? $path : self::siblingPath($path);
            if ($disk->exists($full) && ($size = @getimagesize($disk->path($full))) && ! in_array((int) $size[0], $out, true)) {
                $out[asset('storage/' . $full)] = (int) $size[0];
            }
            asort($out);

            return $out;
        });
    }

    /** "url 240w, url 480w, …" or null when there is no WebP at all. */
    public static function srcset(?string $path): ?string
    {
        $c = self::candidates($path);

        return $c ? implode(', ', array_map(fn ($u, $w) => "$u {$w}w", array_keys($c), $c)) : null;
    }

    /**
     * Everything an <img> (and its preload) needs: a WebP src (the 480px
     * variant, or the largest smaller one) plus the srcset. Null when no
     * WebP exists yet, so callers fall back to the original upload.
     *
     * @return array{src: string, srcset: string}|null
     */
    public static function responsive(?string $path): ?array
    {
        $c = self::candidates($path);
        if (! $c) {
            return null;
        }
        $src = array_key_first($c);
        foreach ($c as $url => $w) {
            if ($w <= 480) {
                $src = $url;
            }
        }

        return ['src' => $src, 'srcset' => self::srcset($path)];
    }

    /**
     * One URL for a fixed-size thumbnail: the smallest WebP at least
     * $minWidth wide, else the largest WebP, else the original file.
     */
    public static function fit(?string $path, int $minWidth): ?string
    {
        if (! $path) {
            return null;
        }
        $c = self::candidates($path);
        foreach ($c as $url => $w) {
            if ($w >= $minWidth) {
                return $url;
            }
        }

        return array_key_last($c) ?? asset('storage/' . $path);
    }

    /** Create the WebP sibling and the width variants. Returns true when any file was written. */
    public static function generate(string $path, bool $force = false): bool
    {
        if (! function_exists('imagewebp')) {
            return false;
        }

        $disk = Storage::disk('public');
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) || ! $disk->exists($path)) {
            return false;
        }

        $src = self::open($ext, $disk->path($path));
        if (! $src) {
            return false;
        }

        $made = false;
        [$w, $h] = [imagesx($src), imagesy($src)];

        if ($ext !== 'webp') {
            $sibling = self::siblingPath($path);
            if ($force || ! $disk->exists($sibling)) {
                $scale = min(1, self::MAX_EDGE / max($w, $h));
                $made = self::write($src, $w, $h, $scale, $disk->path($sibling), self::QUALITY, $disk->size($path)) || $made;
            }
        }

        foreach (self::WIDTHS as $target) {
            if ($target >= $w) {
                break; // never upscale: the full-size file covers this width
            }
            $variant = self::variantPath($path, $target);
            if ($force || ! $disk->exists($variant)) {
                $made = self::write($src, $w, $h, $target / $w, $disk->path($variant), self::VARIANT_QUALITY) || $made;
            }
        }

        imagedestroy($src);
        Cache::forget(self::cacheKey($path));

        return $made;
    }

    public static function delete(?string $path): void
    {
        if (! $path) {
            return;
        }
        $disk = Storage::disk('public');
        $disk->delete(self::siblingPath($path));
        foreach (self::WIDTHS as $w) {
            $disk->delete(self::variantPath($path, $w));
        }
        Cache::forget(self::cacheKey($path));
    }

    private static function open(string $ext, string $file)
    {
        $img = match ($ext) {
            'png' => @imagecreatefrompng($file),
            'webp' => @imagecreatefromwebp($file),
            default => @imagecreatefromjpeg($file),
        };
        if (! $img) {
            return null;
        }
        if (! imageistruecolor($img)) {
            imagepalettetotruecolor($img);
        }
        imagealphablending($img, false);
        imagesavealpha($img, true);

        return $img;
    }

    private static function write($src, int $w, int $h, float $scale, string $dest, int $quality, ?int $maxBytes = null): bool
    {
        $img = $src;
        if ($scale < 1) {
            $img = imagecreatetruecolor(max(1, (int) round($w * $scale)), max(1, (int) round($h * $scale)));
            imagealphablending($img, false);
            imagesavealpha($img, true);
            imagecopyresampled($img, $src, 0, 0, 0, 0, imagesx($img), imagesy($img), $w, $h);
        }
        $ok = imagewebp($img, $dest, $quality);
        if ($img !== $src) {
            imagedestroy($img);
        }

        // A full-size WebP that came out bigger than the original is pointless.
        if ($ok && $maxBytes !== null && filesize($dest) >= $maxBytes) {
            @unlink($dest);

            return false;
        }

        return $ok;
    }

    private static function isWebp(string $path): bool
    {
        return str_ends_with(strtolower($path), '.webp');
    }

    private static function cacheKey(string $path): string
    {
        return 'webp-set:' . md5($path);
    }
}
