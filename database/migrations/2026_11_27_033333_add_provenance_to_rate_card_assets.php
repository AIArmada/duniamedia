<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rate_card_assets', function (Blueprint $table): void {
            $table->string('source_url')->nullable()->after('caption');
            $table->string('source_label')->nullable()->after('source_url');
            $table->string('credit')->nullable()->after('source_label');
            $table->boolean('is_illustrative')->default(false)->after('credit');
        });
    }

    public function down(): void
    {
        Schema::table('rate_card_assets', function (Blueprint $table): void {
            $table->dropColumn(['source_url', 'source_label', 'credit', 'is_illustrative']);
        });
    }
};
