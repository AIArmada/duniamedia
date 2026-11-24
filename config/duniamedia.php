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

    /*
    |--------------------------------------------------------------------------
    | Media advisor AI assistance
    |--------------------------------------------------------------------------
    |
    | The advisor always produces a complete deterministic recommendation.
    | When AI assistance is enabled and a provider credential is present, the
    | deterministic engine is enriched with grounded narrative from the
    | configured Laravel AI provider.
    |
    */
    'advisor' => [
        'ai_enabled' => env('ADVISOR_AI_ENABLED', false),
        'ai_provider' => env('ADVISOR_AI_PROVIDER'),
        'ai_model' => env('ADVISOR_AI_MODEL'),
        'ai_timeout' => (int) env('ADVISOR_AI_TIMEOUT', 45),
    ],

    'admin_email' => env('ADMIN_EMAIL', 'admin@duniamedia.test'),
    'admin_name' => env('ADMIN_NAME', 'Dunia Media Admin'),
    'admin_password' => env('ADMIN_PASSWORD'),
];
