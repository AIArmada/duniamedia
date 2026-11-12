<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_studies', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->text('challenge')->nullable();
            $table->text('approach')->nullable();
            $table->text('outcome')->nullable();
            $table->string('status')->default('draft')->index();
            $table->string('visibility')->default('private')->index();
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('case_study_projects', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('case_study_id')->index();
            $table->foreignUuid('project_id')->index();
            $table->timestampsTz();
        });

        Schema::create('case_study_campaigns', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('case_study_id')->index();
            $table->foreignUuid('campaign_id')->index();
            $table->timestampsTz();
        });

        Schema::create('case_study_brands', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('case_study_id')->index();
            $table->foreignUuid('brand_id')->index();
            $table->timestampsTz();
        });

        Schema::create('case_study_metrics', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('case_study_id')->index();
            $table->string('metric_code');
            $table->string('metric_label');
            $table->decimal('metric_value', 20, 4)->nullable();
            $table->string('unit_code')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestampsTz();
        });

        Schema::create('testimonials', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->nullable()->index();
            $table->foreignUuid('campaign_id')->nullable()->index();
            $table->foreignUuid('brand_id')->nullable()->index();
            $table->foreignUuid('author_party_id')->nullable()->index();
            $table->text('quote_text');
            $table->smallInteger('rating')->nullable();
            $table->string('consent_status')->default('pending')->index();
            $table->string('visibility')->default('private')->index();
            $table->date('approved_on')->nullable();
            $table->timestampsTz();
        });

        Schema::create('client_references', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_party_id')->index();
            $table->foreignUuid('brand_id')->nullable()->index();
            $table->foreignUuid('project_id')->nullable()->index();
            $table->string('reference_type');
            $table->boolean('permission_granted')->default(false);
            $table->string('public_label')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });
    }
};
