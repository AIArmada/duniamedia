<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_pools', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('placement_id')->index();
            $table->string('unit_code');
            $table->string('capacity_model');
            $table->decimal('base_capacity', 18, 3)->nullable();
            $table->string('supply_status')->default('available')->index();
            $table->timestampsTz();
        });

        Schema::create('inventory_windows', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('pool_id')->index();
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at');
            $table->decimal('capacity', 18, 3);
            $table->decimal('reserved_quantity', 18, 3)->default(0);
            $table->string('status')->default('open')->index();
            $table->timestampTz('closed_at')->nullable();
            $table->timestampsTz();
        });

    }
};
