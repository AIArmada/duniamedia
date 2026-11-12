<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creator_profiles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('creator_party_id')->unique();
            $table->string('creator_type');
            $table->string('slug')->unique();
            $table->string('primary_niche')->nullable()->index();
            $table->string('platform_label')->nullable()->index();
            $table->string('handle')->nullable();
            $table->text('bio')->nullable();
            $table->foreignUuid('managed_by_party_id')->nullable()->index();
            $table->string('country_code', 2)->nullable();
            $table->string('location_label')->nullable();
            $table->string('verification_status')->default('pending')->index();
            $table->string('status')->default('active')->index();
            $table->timestampTz('verified_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('pricing_mode')->default('on_request');
            $table->decimal('rate_from', 12, 2)->nullable();
            $table->timestampsTz();
        });

        Schema::create('creator_accounts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('creator_profile_id')->index();
            $table->foreignUuid('media_property_id')->index();
            $table->string('handle')->nullable();
            $table->unsignedBigInteger('followers')->nullable();
            $table->unsignedBigInteger('average_views')->nullable();
            $table->decimal('engagement_rate', 5, 2)->nullable();
            $table->date('metrics_as_of')->nullable();
            $table->timestampsTz();
        });

        Schema::create('creator_delivery_templates', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('creator_profile_id')->index();
            $table->foreignUuid('placement_id')->index();
            $table->foreignUuid('format_id')->nullable()->index();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('usage_rights_days')->nullable();
            $table->unsignedInteger('exclusivity_days')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });
    }
};
