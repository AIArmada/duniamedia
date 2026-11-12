<?php

use App\Models\MediaListing;
use Database\Seeders\RatecardSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Re-import the catalog after the full Drive refresh of 12 Nov 2026.
     * Idempotent: existing listings are updated by slug.
     */
    public function up(): void
    {
        app(RatecardSeeder::class)->run();
    }

    public function down(): void
    {
        MediaListing::whereIn('slug', [
            'sinar-harian-20th-anniversary-2026',
            'malaysiakini-media-kit-2024',
            'malaysiakini-kinitv-event-coverage-2026-2027',
            'sinar-karangkraf-ratecard-2025-2026',
        ])->delete();
    }
};
