<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::create('rate_card_assets', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->index();
            $table->foreignUuid('media_property_id')->nullable()->index();
            $table->string('path');
            $table->string('kind')->default('photo')->index();
            $table->string('alt');
            $table->string('caption')->nullable();
            $table->unsignedInteger('width')->default(0);
            $table->unsignedInteger('height')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });

        Schema::create('rate_card_blocks', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->uuidMorphs('blockable');
            $table->string('type')->index();
            $table->{$json}('payload')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_card_blocks');
        Schema::dropIfExists('rate_card_assets');
    }
};
