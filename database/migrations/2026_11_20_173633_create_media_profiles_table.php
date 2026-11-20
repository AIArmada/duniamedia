<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::create('media_profiles', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->unique();
            $table->foreignUuid('media_owner_party_id')->nullable()->index();
            $table->foreignUuid('creator_profile_id')->nullable()->index();
            $table->string('medium')->index();
            $table->string('unit_code');
            $table->string('pricing_mode')->default('on_request')->index();
            $table->string('availability_status')->default('on_request')->index();
            $table->string('channel_label')->nullable();
            $table->string('location_label')->nullable()->index();
            $table->string('audience_label')->nullable();
            $table->{$json}('specifications')->nullable();
            $table->timestampTz('verified_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('show_rate_card')->default(true);
            $table->text('terms')->nullable();
            $table->decimal('price_from', 12, 2)->nullable();
            $table->foreignUuid('placement_id')->nullable()->index();
            $table->foreignUuid('inventory_window_id')->nullable()->index();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_profiles');
    }
};
