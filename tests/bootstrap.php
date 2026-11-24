<?php

declare(strict_types=1);

$cachedConfig = dirname(__DIR__).'/bootstrap/cache/config.php';

if (is_file($cachedConfig)) {
    unlink($cachedConfig);
}

require dirname(__DIR__).'/vendor/autoload.php';
