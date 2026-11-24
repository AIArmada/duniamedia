<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::create('advisor_sessions', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->string('share_token')->unique();
            $table->string('session_identifier')->index();
            $table->string('status')->default('draft')->index();
            $table->string('generation_mode')->default('catalogue_engine');
            $table->string('objective')->index();
            $table->decimal('budget_amount', 14, 2)->default(0);
            $table->string('budget_flexibility')->default('guided');
            $table->char('currency_code', 3)->default('MYR');
            $table->{$json}('regions')->nullable();
            $table->{$json}('languages')->nullable();
            $table->string('industry')->nullable();
            $table->string('audience')->nullable();
            $table->string('timeline');
            $table->string('launch_window')->nullable();
            $table->{$json}('preferred_mediums')->nullable();
            $table->string('creative_readiness')->default('ready');
            $table->text('brand_context')->nullable();
            $table->{$json}('brief_summary')->nullable();
            $table->{$json}('recommendations')->nullable();
            $table->string('selected_scenario_key')->nullable();
            $table->{$json}('customized_product_ids')->nullable();
            $table->{$json}('customized_services')->nullable();
            $table->string('saved_plan_instance')->nullable()->index();
            $table->foreignUuid('enquiry_id')->nullable()->index();
            $table->string('requester_name')->nullable();
            $table->string('requester_email')->nullable();
            $table->string('requester_company')->nullable();
            $table->string('requester_phone')->nullable();
            $table->timestampTz('generated_at')->nullable();
            $table->timestampTz('submitted_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('advisor_messages', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('advisor_session_id')->index();
            $table->string('role');
            $table->text('content');
            $table->{$json}('metadata')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advisor_messages');
        Schema::dropIfExists('advisor_sessions');
    }
};
