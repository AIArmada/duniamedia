<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('brand_name');
            $table->string('slug')->unique();
            $table->string('website_url')->nullable();
            $table->string('industry')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestampsTz();
        });

        Schema::create('brand_parties', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('brand_id')->index();
            $table->foreignUuid('party_id')->index();
            $table->string('relation_type');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestampsTz();
        });

        Schema::create('advertised_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('brand_id')->index();
            $table->string('item_type');
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('category')->nullable();
            $table->string('landing_page_url')->nullable();
            $table->timestampsTz();
        });

        Schema::create('services', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('service_code')->unique();
            $table->string('slug')->unique();
            $table->string('category')->nullable()->index();
            $table->text('summary');
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('published')->index();
            $table->timestampTz('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });

        Schema::create('projects', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_party_id')->nullable()->index();
            $table->string('project_name');
            $table->string('project_code')->unique();
            $table->string('slug')->unique();
            $table->string('category')->nullable()->index();
            $table->text('summary')->nullable();
            $table->text('brief')->nullable();
            $table->string('status')->default('active')->index();
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('project_parties', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->index();
            $table->foreignUuid('party_id')->index();
            $table->string('role_code');
            $table->timestampsTz();
        });

        Schema::create('project_services', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->index();
            $table->foreignUuid('service_id')->index();
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });

        Schema::create('campaigns', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->nullable()->index();
            $table->foreignUuid('client_party_id')->index();
            $table->foreignUuid('advertiser_party_id')->index();
            $table->foreignUuid('agency_party_id')->nullable()->index();
            $table->foreignUuid('service_id')->nullable()->index();
            $table->string('name');
            $table->string('campaign_code')->unique();
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->string('channel_label')->nullable()->index();
            $table->string('platform_label')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('status')->default('draft')->index();
            $table->decimal('budget_amount', 18, 2)->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('campaign_brands', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('brand_id')->index();
            $table->boolean('is_primary')->default(false);
            $table->timestampsTz();
        });

        Schema::create('campaign_advertised_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('advertised_item_id')->index();
            $table->timestampsTz();
        });

        Schema::create('campaign_objectives', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->index();
            $table->string('objective_code');
            $table->string('kpi_code')->nullable();
            $table->decimal('target_value', 18, 3)->nullable();
            $table->timestampsTz();
        });

        Schema::create('campaign_audiences', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('segment_id')->index();
            $table->foreignUuid('geo_market_id')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });
    }
};
