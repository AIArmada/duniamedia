<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Work media storage
    |--------------------------------------------------------------------------
    |
    | "local" streams the original files from the data/ directory. "s3"
    | redirects playback to a short-lived presigned URL on the configured
    | S3 disk, keeping the bucket private.
    |
    */
    'work_media_disk' => env('WORK_MEDIA_DISK', 'local'),
    'work_media_prefix' => env('WORK_MEDIA_PREFIX', 'client-work'),
    'work_media_url_ttl' => (int) env('WORK_MEDIA_URL_TTL', 120),
];
