<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rate_card_entries', function (Blueprint $table): void {
            $table->boolean('is_plan_selectable')->default(true)->index();
        });
    }

    public function down(): void
    {
        Schema::table('rate_card_entries', function (Blueprint $table): void {
            $table->dropColumn('is_plan_selectable');
        });
    }
};
