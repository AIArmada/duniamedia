<?php

use Database\Seeders\RatecardSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Re-import the catalog after the Target Media 2026 rate-card refresh.
     * Idempotent: existing listings are updated by slug.
     */
    public function up(): void
    {
        app(RatecardSeeder::class)->run();
    }

    public function down(): void
    {
        // The previous 2025 workbook remains attached as an archive document.
    }
};
