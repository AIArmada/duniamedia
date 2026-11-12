<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::create('audience_segments', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('segment_type');
            $table->{$json}('criteria')->default('{}');
            $table->timestampsTz();
        });

        Schema::create('audience_estimates', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('media_property_id')->nullable()->index();
            $table->foreignUuid('placement_id')->nullable()->index();
            $table->foreignUuid('location_id')->nullable()->index();
            $table->foreignUuid('segment_id')->nullable()->index();
            $table->unsignedBigInteger('estimated_reach')->nullable();
            $table->date('sample_date')->nullable();
            $table->string('measurement_method')->nullable();
            $table->string('source')->nullable();
            $table->string('confidence')->nullable();
            $table->timestampsTz();
        });

        Schema::create('inventory_pools', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('placement_id')->index();
            $table->string('unit_code');
            $table->string('capacity_model');
            $table->decimal('base_capacity', 18, 3)->nullable();
            $table->string('supply_status')->default('available')->index();
            $table->timestampsTz();
        });

        Schema::create('inventory_windows', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('pool_id')->index();
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at');
            $table->decimal('capacity', 18, 3);
            $table->decimal('reserved_quantity', 18, 3)->default(0);
            $table->string('status')->default('open')->index();
            $table->timestampTz('closed_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('inventory_blackouts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('pool_id')->index();
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at');
            $table->string('reason')->nullable();
            $table->timestampsTz();
        });

        Schema::create('inventory_holds', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('inventory_window_id')->index();
            $table->foreignUuid('requested_by_party_id')->index();
            $table->decimal('quantity', 18, 3);
            $table->timestampTz('expires_at');
            $table->string('status')->default('held')->index();
            $table->timestampTz('released_at')->nullable();
            $table->timestampTz('converted_at')->nullable();
            $table->timestampsTz();
        });
    }
};
