<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geo_markets', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('parent_market_id')->nullable()->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('market_type');
            $table->string('country_code', 2)->nullable()->index();
            $table->timestampsTz();
        });

        Schema::create('locations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('location_type');
            $table->string('name');
            $table->string('venue_type')->nullable();
            $table->string('site_type')->nullable();
            $table->foreignUuid('geo_market_id')->nullable()->index();
            $table->boolean('is_public')->default(true);
            $table->string('status')->default('active')->index();
            $table->timestampsTz();
        });

        Schema::create('location_parties', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('location_id')->index();
            $table->foreignUuid('party_id')->index();
            $table->string('role_code');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestampsTz();
        });

        Schema::create('location_zones', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('location_id')->index();
            $table->foreignUuid('parent_zone_id')->nullable()->index();
            $table->string('name');
            $table->string('zone_type');
            $table->string('floor_label')->nullable();
            $table->timestampsTz();
        });

        Schema::create('host_structures', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('location_id')->index();
            $table->foreignUuid('zone_id')->nullable()->index();
            $table->foreignUuid('owner_party_id')->nullable()->index();
            $table->string('structure_type');
            $table->string('label');
            $table->string('external_code')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestampsTz();
        });

        Schema::create('site_permits', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('location_id')->index();
            $table->foreignUuid('asset_id')->nullable()->index();
            $table->foreignUuid('issuing_party_id')->nullable()->index();
            $table->string('permit_type');
            $table->string('permit_reference')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestampsTz();
        });

        Schema::create('traffic_observations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('location_id')->index();
            $table->date('observed_on');
            $table->string('metric_code');
            $table->decimal('estimated_value', 18, 2);
            $table->string('unit_code');
            $table->string('source')->nullable();
            $table->string('confidence')->nullable();
            $table->timestampsTz();
        });
    }
};
