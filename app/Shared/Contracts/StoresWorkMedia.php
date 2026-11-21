<?php

declare(strict_types=1);

namespace App\Shared\Contracts;

interface StoresWorkMedia
{
    /**
     * Resolve a readable local path for a stored work-media file, or null when
     * the file is not held on local disk.
     */
    public function localPath(string $fileName): ?string;

    /**
     * Resolve a short-lived public URL for a stored work-media file, or null
     * when the file cannot be addressed remotely.
     */
    public function temporaryUrl(string $fileName, string $mimeType): ?string;
}
