<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_categories', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('parent_id')->nullable()->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });

        Schema::create('media_types', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('category_id')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('medium_code')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });

        Schema::create('platforms', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('platform_kind');
            $table->foreignUuid('owner_party_id')->nullable()->index();
            $table->string('website_url')->nullable();
            $table->timestampsTz();
        });

        Schema::create('media_properties', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_party_id')->nullable()->index();
            $table->string('property_type');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('url')->nullable();
            $table->string('status')->default('active')->index();
            $table->text('description')->nullable();
            $table->string('brand_mark')->nullable();
            $table->string('accent_color', 16)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });

        Schema::create('property_platforms', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('media_property_id')->index();
            $table->foreignUuid('platform_id')->index();
            $table->string('handle')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestampsTz();
        });

        Schema::create('property_media_types', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('media_property_id')->index();
            $table->foreignUuid('media_type_id')->index();
            $table->timestampsTz();
        });

        Schema::create('property_parties', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('media_property_id')->index();
            $table->foreignUuid('party_id')->index();
            $table->string('role_code');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestampsTz();
        });

        Schema::create('network_members', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('network_property_id')->index();
            $table->foreignUuid('member_property_id')->index();
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->timestampsTz();
        });
    }
};
