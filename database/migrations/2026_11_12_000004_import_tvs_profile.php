<?php

use Database\Seeders\RatecardSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Re-import the catalog after adding the TVS Profile to the TVS listing.
     * Idempotent: existing listings are updated by slug.
     */
    public function up(): void
    {
        app(RatecardSeeder::class)->run();
    }

    public function down(): void
    {
        // Enrichment only; the TVS and billboard listings remain published.
    }
};
