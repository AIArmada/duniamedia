<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parties', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('person_id')->nullable()->index();
            $table->string('party_type');
            $table->string('display_name');
            $table->string('slug')->unique();
            $table->string('legal_name')->nullable();
            $table->string('status')->default('active')->index();
            $table->string('website_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });

        Schema::create('organizations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('party_id')->unique();
            $table->string('registration_no')->nullable();
            $table->string('tax_no')->nullable();
            $table->string('industry')->nullable();
            $table->string('organization_type')->nullable();
            $table->foreignUuid('parent_organization_id')->nullable()->index();
            $table->timestampsTz();
        });

        Schema::create('party_roles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('party_id')->index();
            $table->string('role_code')->index();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });

        Schema::create('party_relationships', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('from_party_id')->index();
            $table->foreignUuid('to_party_id')->index();
            $table->string('relationship_type')->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });
    }
};
