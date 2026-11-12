<?php

use App\Models\MediaListing;
use Database\Seeders\RatecardSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Re-import the rate-card catalog after the 12 Nov 2026 additions.
     * Idempotent: existing listings are updated by slug.
     */
    public function up(): void
    {
        app(RatecardSeeder::class)->run();
    }

    public function down(): void
    {
        MediaListing::whereIn('slug', [
            'ra-media-relations-news-slot-2026-2027',
            'ra-media-relations-live-tv-talkshow-2026-2027',
            'tvs-sponsorship-spot-buy-2026',
            'billboard-sarawak-smg-2026',
            'ikim-fm-ramadan-2027',
            'media-prima-audio-trade-show-2025',
            'astro-upfront-2027',
        ])->delete();
    }
};
