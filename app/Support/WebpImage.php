<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * WebP siblings for uploaded images on the public disk.
 *
 * "products/abc.jpg" gets "products/abc.jpg.webp" next to it (the suffix
 * form can never collide with another upload). Views use url() to add a
 * <source type="image/webp">; when the sibling is missing — GD without WebP,
 * or not generated yet — they simply fall back to the original file.
 */
class WebpImage
{
    public const MAX_EDGE = 1200;
    public const QUALITY = 80;

    public static function siblingPath(string $path): string
    {
        return $path . '.webp';
    }

    /** Public URL of the WebP sibling, or null when there is none / the source is already WebP. */
    public static function url(?string $path): ?string
    {
        if (! $path || str_ends_with(strtolower($path), '.webp')) {
            return null;
        }
        $sibling = self::siblingPath($path);

        return Storage::disk('public')->exists($sibling) ? asset('storage/' . $sibling) : null;
    }

    /** Create the WebP sibling. Returns true when a file was written. */
    public static function generate(string $path, bool $force = false): bool
    {
        if (! function_exists('imagewebp')) {
            return false;
        }

        $disk = Storage::disk('public');
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($ext, ['jpg', 'jpeg', 'png'], true) || ! $disk->exists($path)) {
            return false;
        }

        $sibling = self::siblingPath($path);
        if (! $force && $disk->exists($sibling)) {
            return false;
        }

        $src = @($ext === 'png' ? imagecreatefrompng($disk->path($path)) : imagecreatefromjpeg($disk->path($path)));
        if (! $src) {
            return false;
        }

        [$w, $h] = [imagesx($src), imagesy($src)];
        $scale = min(1, self::MAX_EDGE / max($w, $h));
        if ($scale < 1) {
            $nw = (int) round($w * $scale);
            $nh = (int) round($h * $scale);
            $dst = imagecreatetruecolor($nw, $nh);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagedestroy($src);
            $src = $dst;
        } elseif ($ext === 'png') {
            imagepalettetotruecolor($src);
            imagealphablending($src, false);
            imagesavealpha($src, true);
        }

        $ok = imagewebp($src, $disk->path($sibling), self::QUALITY);
        imagedestroy($src);

        // A WebP that came out bigger than the original is pointless.
        if ($ok && $disk->size($sibling) >= $disk->size($path)) {
            $disk->delete($sibling);
            return false;
        }

        return $ok;
    }

    public static function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete(self::siblingPath($path));
        }
    }
}
