<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::table('media_properties', function (Blueprint $table) use ($json): void {
            $table->string('medium')->nullable()->index();
            $table->string('category')->nullable()->index();
            $table->text('audience')->nullable();
            $table->{$json}('stats')->nullable();
            $table->string('hero_path')->nullable();
            $table->boolean('is_public')->default(true)->index();
        });

        Schema::table('media_profiles', function (Blueprint $table) use ($json): void {
            $table->string('validity')->nullable();
            $table->{$json}('stats')->nullable();
            $table->{$json}('highlights')->nullable();
        });

        Schema::table('rate_card_entries', function (Blueprint $table): void {
            $table->foreignUuid('section_id')->nullable()->index();
            $table->string('table_key')->nullable()->index();
            $table->string('row_label')->nullable();
            $table->string('column_label')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_package')->default(false);
            $table->string('validity')->nullable();
            $table->text('terms')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rate_card_entries', function (Blueprint $table): void {
            $table->dropColumn([
                'section_id',
                'table_key',
                'row_label',
                'column_label',
                'sort_order',
                'is_package',
                'validity',
                'terms',
            ]);
        });

        Schema::table('media_profiles', function (Blueprint $table): void {
            $table->dropColumn(['validity', 'stats', 'highlights']);
        });

        Schema::table('media_properties', function (Blueprint $table): void {
            $table->dropColumn(['medium', 'category', 'audience', 'stats', 'hero_path', 'is_public']);
        });
    }
};
