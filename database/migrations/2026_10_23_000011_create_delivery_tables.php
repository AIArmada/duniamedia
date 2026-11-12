<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::create('creative_assets', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->nullable()->index();
            $table->foreignUuid('owner_party_id')->nullable()->index();
            $table->foreignUuid('format_id')->nullable()->index();
            $table->string('name');
            $table->string('creative_kind');
            $table->unsignedInteger('version')->default(1);
            $table->timestampsTz();
        });

        Schema::create('creative_licenses', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('creative_asset_id')->index();
            $table->foreignUuid('licensor_party_id')->index();
            $table->foreignUuid('licensee_party_id')->index();
            $table->foreignUuid('rights_agreement_id')->nullable()->index();
            $table->string('license_type');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('paid_amplification_allowed')->default(false);
            $table->timestampsTz();
        });

        Schema::create('creative_approvals', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('creative_asset_id')->index();
            $table->foreignUuid('approver_party_id')->nullable()->index();
            $table->foreignUuid('placement_id')->nullable()->index();
            $table->string('status')->default('pending')->index();
            $table->timestampTz('decided_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });

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

        Schema::create('creator_deliverables', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('flight_placement_id')->index();
            $table->foreignUuid('creator_profile_id')->index();
            $table->foreignUuid('creator_account_id')->nullable()->index();
            $table->string('content_url')->nullable();
            $table->timestampTz('published_at')->nullable();
            $table->string('status')->default('planned')->index();
            $table->timestampsTz();
        });

        Schema::create('delivery_events', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('flight_placement_id')->index();
            $table->string('event_type');
            $table->timestampTz('event_at');
            $table->decimal('quantity', 18, 3)->nullable();
            $table->string('source')->nullable();
            $table->string('external_event_id')->nullable()->index();
            $table->{$json}('payload')->default('{}');
            $table->timestampsTz();
        });

        Schema::create('delivery_proofs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('flight_placement_id')->index();
            $table->string('proof_type');
            $table->string('proof_url')->nullable();
            $table->timestampTz('recorded_at');
            $table->foreignUuid('verified_by_party_id')->nullable()->index();
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

        Schema::create('campaign_reports', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->index();
            $table->string('report_name');
            $table->date('reporting_from')->nullable();
            $table->date('reporting_to')->nullable();
            $table->{$json}('summary')->default('{}');
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
        });
    }
};
