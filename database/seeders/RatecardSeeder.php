<?php

namespace Database\Seeders;

use App\Models\MediaListing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RatecardSeeder extends Seeder
{
    public function run(): void
    {
        $path = resource_path('data/ratecards-2026.json');
        if (! File::exists($path)) {
            return;
        }

        $data = json_decode(File::get($path), true);
        foreach ($data['listings'] ?? [] as $item) {
            MediaListing::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'title' => $item['title'],
                    'type' => $item['type'],
                    'channel' => $item['channel'] ?? null,
                    'location' => $item['location'] ?? null,
                    'audience' => $item['audience'] ?? null,
                    'summary' => $item['summary'],
                    'description' => $item['description'] ?? null,
                    'image' => $item['image'] ?? null,
                    'specifications' => $item['specifications'] ?? null,
                    'price_from' => $item['price_from'] ?? null,
                    'pricing_mode' => $item['pricing_mode'] ?? 'on_request',
                    'availability_status' => $item['availability_status'] ?? 'on_request',
                    'supplier_name' => $item['supplier_name'] ?? null,
                    'is_featured' => (bool) ($item['is_featured'] ?? false),
                    'is_published' => (bool) ($item['is_published'] ?? true),
                ]
            );
        }

        // Allowed-only: remove any listings derived from restricted view-only sources.
        MediaListing::whereIn('slug', [
            'astro-islamic-program-viewonly',
            'omnia-rr2027-master-deck-viewonly',
            'the-edge-fy2026-content-viewonly',
            'ciq-checkpoint-packages-viewonly',
            'impact-premium-ooh-2-3-viewonly',
            'kempen-ramadan-syawal-viewonly',
            'imedia-network-2025-26-blitz',
        ])->delete();
    }
}
