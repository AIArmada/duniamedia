<?php

namespace Database\Seeders;

use App\Features\Catalog\Persistence\WriteMediaProperties;
use Illuminate\Database\Seeder;

class MediaPropertyVisibilitySeeder extends Seeder
{
    public function run(): void
    {
        $slugs = [];

        foreach (glob(resource_path('data/rate-cards/*.json')) ?: [] as $file) {
            $data = json_decode((string) file_get_contents($file), true);

            foreach ($data['channels'] ?? [] as $channel) {
                if (! empty($channel['slug'])) {
                    $slugs[] = $channel['slug'];
                }
            }
        }

        $slugs = array_values(array_unique($slugs));

        app(WriteMediaProperties::class)->publishOnly($slugs);

        $this->command?->info('Published '.count($slugs).' canonical rate-card channels.');
    }
}
