<?php

declare(strict_types=1);

return [
    'site_key' => env('TURNSTILE_SITE_KEY', '0x4AAAAAAGQTDgQG9MAgRCf6'),
    'secret_key' => env('TURNSTILE_SECRET_KEY'),
    'hostname' => env('TURNSTILE_HOSTNAME', 'duniamedia.masyuk.com'),
];
