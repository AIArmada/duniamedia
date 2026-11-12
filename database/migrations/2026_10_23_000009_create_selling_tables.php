<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::create('media_offerings', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('seller_party_id')->index();
            $table->foreignUuid('creator_profile_id')->nullable()->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('offering_type')->index();
            $table->string('unit_code');
            $table->text('summary');
            $table->text('description')->nullable();
            $table->string('pricing_mode')->default('on_request');
            $table->decimal('price_from', 12, 2)->nullable();
            $table->string('availability_status')->default('on_request')->index();
            $table->string('channel_label')->nullable();
            $table->string('location_label')->nullable()->index();
            $table->string('audience_label')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('status')->default('draft')->index();
            $table->timestampTz('published_at')->nullable();
            $table->timestampTz('archived_at')->nullable();
            $table->timestampTz('verified_at')->nullable();
            $table->{$json}('specifications')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });

        Schema::create('offering_placements', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('offering_id')->index();
            $table->foreignUuid('placement_id')->index();
            $table->decimal('units_per_purchase', 18, 3)->default(1);
            $table->timestampsTz();
        });

        Schema::create('packages', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('seller_party_id')->index();
            $table->string('package_name');
            $table->string('package_code')->unique();
            $table->text('description')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestampsTz();
        });

        Schema::create('package_lines', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('package_id')->index();
            $table->foreignUuid('offering_id')->index();
            $table->decimal('quantity', 18, 3)->default(1);
            $table->timestampsTz();
        });

        Schema::create('rate_cards', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('seller_party_id')->index();
            $table->string('name');
            $table->string('currency_code', 3)->default('MYR');
            $table->date('effective_from');
            $table->date('effective_until')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestampsTz();
        });

        Schema::create('rate_card_lines', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('rate_card_id')->index();
            $table->foreignUuid('offering_id')->index();
            $table->string('pricing_model');
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->decimal('minimum_quantity', 18, 3)->default(1);
            $table->timestampsTz();
        });

        Schema::create('enquiries', function (Blueprint $table) use ($json): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('requester_party_id')->index();
            $table->foreignUuid('campaign_id')->nullable()->index();
            $table->string('source')->default('website')->index();
            $table->string('subject');
            $table->text('brief')->nullable();
            $table->decimal('budget_amount', 18, 2)->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->string('status')->default('new')->index();
            $table->timestampTz('consent_at')->nullable();
            $table->timestampTz('qualified_at')->nullable();
            $table->timestampTz('proposal_at')->nullable();
            $table->timestampTz('won_at')->nullable();
            $table->timestampTz('lost_at')->nullable();
            $table->timestampTz('closed_at')->nullable();
            $table->{$json}('metadata')->nullable();
            $table->timestampsTz();
        });

        Schema::create('quotes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('enquiry_id')->nullable()->index();
            $table->foreignUuid('campaign_id')->nullable()->index();
            $table->foreignUuid('seller_party_id')->index();
            $table->foreignUuid('buyer_party_id')->index();
            $table->string('quote_number')->unique();
            $table->string('currency_code', 3)->default('MYR');
            $table->string('status')->default('requested')->index();
            $table->string('price_visibility')->default('private');
            $table->text('notes')->nullable();
            $table->date('valid_until')->nullable();
            $table->decimal('subtotal_amount', 18, 2)->default(0);
            $table->timestampTz('sent_at')->nullable();
            $table->timestampTz('accepted_at')->nullable();
            $table->timestampTz('declined_at')->nullable();
            $table->timestampTz('expired_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('quote_lines', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('quote_id')->index();
            $table->foreignUuid('offering_id')->nullable()->index();
            $table->foreignUuid('package_id')->nullable()->index();
            $table->string('kind')->default('media');
            $table->text('description');
            $table->decimal('quantity', 18, 3)->default(1);
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->decimal('line_total', 18, 2)->default(0);
            $table->timestampsTz();
        });

        Schema::create('bookings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('quote_id')->nullable()->index();
            $table->foreignUuid('campaign_id')->nullable()->index();
            $table->foreignUuid('buyer_party_id')->index();
            $table->foreignUuid('seller_party_id')->index();
            $table->string('booking_number')->unique();
            $table->string('status')->default('pending')->index();
            $table->string('currency_code', 3)->default('MYR');
            $table->timestampTz('booked_at')->nullable();
            $table->decimal('net_amount', 18, 2)->default(0);
            $table->timestampTz('confirmed_at')->nullable();
            $table->timestampTz('cancelled_at')->nullable();
            $table->timestampTz('completed_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('booking_lines', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->index();
            $table->foreignUuid('offering_id')->index();
            $table->foreignUuid('placement_id')->nullable()->index();
            $table->decimal('quantity', 18, 3)->default(1);
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->timestampTz('starts_at')->nullable();
            $table->timestampTz('ends_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('inventory_allocations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_line_id')->index();
            $table->foreignUuid('inventory_window_id')->index();
            $table->foreignUuid('hold_id')->nullable()->index();
            $table->decimal('quantity', 18, 3);
            $table->string('status')->default('allocated')->index();
            $table->timestampTz('released_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('purchase_orders', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->index();
            $table->string('buyer_po_number');
            $table->date('issued_on')->nullable();
            $table->decimal('amount', 18, 2)->nullable();
            $table->timestampsTz();
        });

        Schema::create('invoices', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->index();
            $table->string('invoice_number')->unique();
            $table->string('currency_code', 3)->default('MYR');
            $table->date('issued_on');
            $table->date('due_on')->nullable();
            $table->string('status')->default('draft')->index();
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->timestampTz('paid_at')->nullable();
            $table->timestampTz('voided_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('invoice_lines', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->index();
            $table->foreignUuid('booking_line_id')->nullable()->index();
            $table->text('description');
            $table->decimal('quantity', 18, 3)->default(1);
            $table->decimal('line_total', 18, 2)->default(0);
            $table->timestampsTz();
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->index();
            $table->string('payment_reference')->nullable();
            $table->timestampTz('paid_at')->nullable();
            $table->decimal('amount', 18, 2);
            $table->string('currency_code', 3)->default('MYR');
            $table->string('status')->default('pending')->index();
            $table->timestampsTz();
        });
    }
};
