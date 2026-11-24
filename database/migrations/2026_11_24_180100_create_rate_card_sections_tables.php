<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_card_sections', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->index();
            $table->string('key');
            $table->string('title');
            $table->text('intro')->nullable();
            $table->string('medium')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();

            $table->unique(['product_id', 'key']);
        });

        Schema::create('rate_card_section_media_property', function (Blueprint $table): void {
            $table->foreignUuid('section_id')->index();
            $table->foreignUuid('media_property_id')->index();
            $table->timestampsTz();

            $table->unique(['section_id', 'media_property_id'], 'rate_card_section_channel_unique');
        });

        Schema::create('media_profile_media_property', function (Blueprint $table): void {
            $table->foreignUuid('media_profile_id')->index();
            $table->foreignUuid('media_property_id')->index();
            $table->timestampsTz();

            $table->unique(['media_profile_id', 'media_property_id'], 'media_profile_channel_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_profile_media_property');
        Schema::dropIfExists('rate_card_section_media_property');
        Schema::dropIfExists('rate_card_sections');
    }
};
