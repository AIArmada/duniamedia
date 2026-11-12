<?php

namespace Database\Seeders;

use AIArmada\Signals\Models\TrackedProperty;
use Illuminate\Database\Seeder;

class SignalSeeder extends Seeder
{
    public function run(): void
    {
        TrackedProperty::updateOrCreate(
            ['slug' => 'duniamedia-website'],
            [
                'name' => 'Dunia Media Website',
                'domain' => parse_url((string) config('app.url'), PHP_URL_HOST),
                'type' => 'website',
                'timezone' => config('app.timezone'),
                'currency' => 'MYR',
                'is_active' => true,
            ],
        );
    }
}
