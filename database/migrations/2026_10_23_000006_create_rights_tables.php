<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rights_agreements', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('agreement_type')->index();
            $table->string('agreement_no')->nullable();
            $table->string('name');
            $table->foreignUuid('issuer_party_id')->index();
            $table->foreignUuid('holder_party_id')->index();
            $table->date('signed_on')->nullable();
            $table->date('valid_from');
            $table->date('valid_to')->nullable();
            $table->string('status')->default('active')->index();
            $table->string('exclusivity')->default('non_exclusive');
            $table->decimal('revenue_share_pct', 5, 2)->nullable();
            $table->decimal('minimum_guarantee_amount', 18, 2)->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->timestampTz('expired_at')->nullable();
            $table->timestampTz('terminated_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('rights_scopes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('agreement_id')->index();
            $table->foreignUuid('location_id')->nullable()->index();
            $table->foreignUuid('host_structure_id')->nullable()->index();
            $table->foreignUuid('asset_id')->nullable()->index();
            $table->foreignUuid('media_property_id')->nullable()->index();
            $table->foreignUuid('placement_id')->nullable()->index();
            $table->text('scope_notes')->nullable();
            $table->timestampsTz();
        });

        Schema::create('rights_permissions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('agreement_id')->index();
            $table->string('permission_code');
            $table->boolean('is_allowed')->default(true);
            $table->text('restriction_notes')->nullable();
            $table->timestampsTz();
        });

        Schema::create('sales_mandates', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('principal_party_id')->index();
            $table->foreignUuid('sales_party_id')->index();
            $table->foreignUuid('agreement_id')->nullable()->index();
            $table->foreignUuid('media_property_id')->nullable()->index();
            $table->foreignUuid('placement_id')->nullable()->index();
            $table->boolean('is_exclusive')->default(false);
            $table->date('valid_from');
            $table->date('valid_to')->nullable();
            $table->decimal('commission_pct', 5, 2)->nullable();
            $table->timestampTz('terminated_at')->nullable();
            $table->timestampsTz();
        });
    }
};
