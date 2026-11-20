<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('rate_card_lines');
        Schema::dropIfExists('rate_cards');
        Schema::dropIfExists('package_lines');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('offering_placements');
        Schema::dropIfExists('media_offerings');
    }

    public function down(): void
    {
        // The legacy catalogue is intentionally unrecoverable.
    }
};
