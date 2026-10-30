<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MediaImage
{
    public static function url(?string $path, string $fallback = 'assets/img/outdoor.webp'): string
    {
        $path = $path ?: $fallback;

        if (str_starts_with($path, 'assets/')) {
            $publicPath = public_path($path);
            $version = is_file($publicPath) ? filemtime($publicPath) : null;

            return asset($path).($version ? '?v='.$version : '');
        }

        return Storage::disk('public')->url($path);
    }

    public static function srcset(?string $path, array $widths = []): ?string
    {
        if (!$path || !str_starts_with($path, 'assets/')) {
            return null;
        }

        $sourcePath = public_path($path);
        if (!is_file($sourcePath)) {
            return null;
        }

        $imageSize = getimagesize($sourcePath);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($imageSize === false || $extension === '') {
            return null;
        }

        $sourceWidth = (int) $imageSize[0];
        $basePath = substr($path, 0, -strlen($extension) - 1);
        $candidates = [];

        foreach (array_unique($widths) as $width) {
            $width = filter_var($width, FILTER_VALIDATE_INT);
            if ($width === false || $width <= 0 || $width >= $sourceWidth) {
                continue;
            }

            $candidatePath = "{$basePath}-{$width}.{$extension}";
            if (is_file(public_path($candidatePath))) {
                $candidates[$width] = self::url($candidatePath)." {$width}w";
            }
        }

        $candidates[$sourceWidth] = self::url($path)." {$sourceWidth}w";
        ksort($candidates, SORT_NUMERIC);

        return implode(', ', array_values($candidates));
    }
}
