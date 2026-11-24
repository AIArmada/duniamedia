<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_card_visuals', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->index();
            $table->foreignUuid('media_profile_id')->nullable()->index();
            $table->string('source_file')->index();
            $table->string('document_name')->nullable();
            $table->string('kind')->default('page')->index();
            $table->unsignedSmallInteger('page')->nullable();
            $table->string('path');
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->unsignedInteger('bytes');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_card_visuals');
    }
};
