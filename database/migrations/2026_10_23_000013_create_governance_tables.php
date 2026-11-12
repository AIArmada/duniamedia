<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::create('record_verifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('entity_type')->index();
            $table->uuid('entity_id')->index();
            $table->string('verification_type')->index();
            $table->string('status')->default('pending')->index();
            $table->foreignUuid('verifier_party_id')->nullable()->index();
            $table->timestampTz('verified_at')->nullable();
            $table->timestampTz('expired_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('external_references', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->string('entity_type')->index();
            $table->uuid('entity_id')->index();
            $table->string('external_system')->index();
            $table->string('external_id');
            $table->{$json}('metadata')->default('{}');
            $table->timestampsTz();
        });

        Schema::create('audit_events', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('actor_party_id')->nullable()->index();
            $table->string('entity_type')->index();
            $table->uuid('entity_id')->index();
            $table->string('action')->index();
            $table->timestampTz('changed_at');
            $table->{$json}('before_data')->nullable();
            $table->{$json}('after_data')->nullable();
            $table->timestampsTz();
        });
    }
};
