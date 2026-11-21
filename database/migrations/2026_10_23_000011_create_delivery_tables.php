<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_flights', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('booking_id')->nullable()->index();
            $table->string('name');
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at');
            $table->string('status')->default('planned')->index();
            $table->timestampsTz();
        });

        Schema::create('flight_placements', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('flight_id')->index();
            $table->foreignUuid('booking_line_id')->nullable()->index();
            $table->foreignUuid('placement_id')->index();
            $table->foreignUuid('creative_asset_id')->nullable()->index();
            $table->decimal('scheduled_quantity', 18, 3)->nullable();
            $table->timestampsTz();
        });

        Schema::create('performance_metrics', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('flight_placement_id')->nullable()->index();
            $table->date('metric_date');
            $table->string('metric_code');
            $table->decimal('metric_value', 20, 4);
            $table->string('unit_code')->nullable();
            $table->string('source')->nullable();
            $table->boolean('is_estimate')->default(false);
            $table->timestampsTz();
        });
    }
};
