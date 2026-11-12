<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::create('media_assets', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('media_property_id')->nullable()->index();
            $table->foreignUuid('location_id')->nullable()->index();
            $table->foreignUuid('host_structure_id')->nullable()->index();
            $table->string('asset_type');
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->string('status')->default('active')->index();
            $table->timestampTz('installed_at')->nullable();
            $table->{$json}('specs')->default('{}');
            $table->timestampsTz();
        });

        Schema::create('asset_parties', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('asset_id')->index();
            $table->foreignUuid('party_id')->index();
            $table->string('role_code');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestampsTz();
        });

        Schema::create('asset_surfaces', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('asset_id')->index();
            $table->string('label');
            $table->string('surface_type');
            $table->decimal('width_mm', 12, 2)->nullable();
            $table->decimal('height_mm', 12, 2)->nullable();
            $table->unsignedInteger('resolution_width_px')->nullable();
            $table->unsignedInteger('resolution_height_px')->nullable();
            $table->timestampsTz();
        });

        Schema::create('ad_formats', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('format_code')->unique();
            $table->string('creative_type');
            $table->unsignedInteger('default_duration_seconds')->nullable();
            $table->{$json}('specification')->default('{}');
            $table->timestampsTz();
        });

        Schema::create('placements', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('media_property_id')->nullable()->index();
            $table->foreignUuid('asset_surface_id')->nullable()->index();
            $table->string('placement_code')->unique();
            $table->string('name');
            $table->string('placement_type')->index();
            $table->string('status')->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });

        Schema::create('placement_formats', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('placement_id')->index();
            $table->foreignUuid('format_id')->index();
            $table->boolean('is_default')->default(false);
            $table->timestampsTz();
        });

        Schema::create('placement_specs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('placement_id')->index();
            $table->string('spec_key');
            $table->text('spec_value');
            $table->boolean('is_required')->default(false);
            $table->timestampsTz();
        });

        Schema::create('asset_networks', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('asset_id')->index();
            $table->foreignUuid('network_property_id')->index();
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->timestampsTz();
        });
    }
};
