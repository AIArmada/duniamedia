<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

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
