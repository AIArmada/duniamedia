<?php

use App\Models\MediaListing;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Import the curated 2026 rate-card catalog. Idempotent: safe to re-run.
     */
    public function up(): void
    {
        $path = resource_path('data/ratecards-2026.json');
        if (! File::exists($path)) {
            return;
        }

        $data = json_decode(File::get($path), true);
        if (! is_array($data)) {
            return;
        }

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

        // Never publish listings sourced from owner-restricted files.
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

    public function down(): void
    {
        $path = resource_path('data/ratecards-2026.json');
        $slugs = [];

        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            $slugs = array_column($data['listings'] ?? [], 'slug');
        }

        if ($slugs !== []) {
            MediaListing::whereIn('slug', $slugs)->delete();
        }
    }
};
