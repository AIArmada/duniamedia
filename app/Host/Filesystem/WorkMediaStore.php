<?php

declare(strict_types=1);

namespace App\Host\Filesystem;

use App\Shared\Contracts\StoresWorkMedia;
use Illuminate\Support\Facades\Storage;

final readonly class WorkMediaStore implements StoresWorkMedia
{
    public function localPath(string $fileName): ?string
    {
        $path = storage_path('app/private/work/'.$fileName);

        return is_file($path) ? $path : null;
    }

    public function temporaryUrl(string $fileName, string $mimeType): ?string
    {
        if (config('duniamedia.work_media_disk') !== 's3') {
            return null;
        }

        $disk = Storage::disk('s3');
        $remote = trim((string) config('duniamedia.work_media_prefix', 'client-work'), '/').'/'.$fileName;

        if (! $disk->exists($remote)) {
            return null;
        }

        return $disk->temporaryUrl(
            $remote,
            now()->addMinutes((int) config('duniamedia.work_media_url_ttl', 120)),
            [
                'ResponseContentType' => $mimeType,
                'ResponseContentDisposition' => 'inline',
            ],
        );
    }
}
