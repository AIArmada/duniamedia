<?php

namespace App\Support;

class ClientWork
{
    private static ?array $data = null;

    public static function data(): array
    {
        if (self::$data === null) {
            $path = resource_path('data/client-work.json');
            self::$data = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        }

        return self::$data;
    }

    public static function media(): array
    {
        return self::data()['media'];
    }
}
