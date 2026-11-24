<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_card_entries', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->index();
            $table->foreignUuid('media_profile_id')->nullable()->index();
            $table->string('source_file')->index();
            $table->unsignedSmallInteger('page');
            $table->string('section')->nullable();
            $table->text('item');
            $table->string('unit')->nullable();
            $table->decimal('price_rm', 12, 2)->nullable();
            $table->decimal('price_incl_sst_rm', 12, 2)->nullable();
            $table->string('price_basis')->nullable();
            $table->string('confidence')->nullable();
            $table->text('notes')->nullable();
            $table->text('source_text')->nullable();
            $table->timestampsTz();
        });

        Schema::create('rate_card_notes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->nullable()->index();
            $table->string('source_file')->nullable()->index();
            $table->unsignedSmallInteger('page')->nullable();
            $table->string('kind')->default('gap')->index();
            $table->text('note');
            $table->text('raw')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_card_notes');
        Schema::dropIfExists('rate_card_entries');
    }
};
