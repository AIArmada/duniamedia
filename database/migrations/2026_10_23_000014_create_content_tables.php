<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable()->index();
            $table->text('excerpt');
            $table->longText('body');
            $table->string('status')->default('draft')->index();
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('trainings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable()->index();
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->string('format')->nullable();
            $table->string('duration')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
        });
    }
};
