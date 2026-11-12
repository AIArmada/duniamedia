<?php

namespace Database\Seeders\Concerns;

use Spatie\MediaLibrary\HasMedia;

trait SeedsMedia
{
    protected function mediaFile(
        HasMedia $model,
        ?string $path,
        string $collection = 'image',
        string $disk = 'public',
        array $custom = [],
    ): void {
        if (! $path) {
            return;
        }

        $fullPath = $this->resolveMediaPath($path, $disk);

        if ($fullPath === null) {
            return;
        }

        $model->addMedia($fullPath)
            ->preservingOriginal()
            ->withCustomProperties($custom)
            ->toMediaCollection($collection, $disk === 'public' ? config('media-library.disk_name') : 'media-private');
    }

    private function resolveMediaPath(string $path, string $disk): ?string
    {
        if ($disk !== 'public') {
            $fullPath = storage_path('app/private/'.$path);

            return is_file($fullPath) ? $fullPath : null;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            return $fullPath;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $base = substr($path, 0, -strlen($extension) - 1);

        foreach (['-960', '-480', '-1536', '-640', '-320'] as $suffix) {
            $candidate = public_path($base.$suffix.'.'.$extension);

            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    protected function replaceMedia(
        HasMedia $model,
        ?string $path,
        string $collection = 'image',
        string $disk = 'public',
        array $custom = [],
    ): void {
        $model->clearMediaCollection($collection);

        $this->mediaFile($model, $path, $collection, $disk, $custom);
    }
}
