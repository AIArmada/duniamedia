<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $json = config('database.json_column_type', 'jsonb');

        Schema::dropIfExists('quote_lines');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('booking_lines');
        Schema::dropIfExists('bookings');

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
            $table->foreignUuid('product_id')->nullable()->index();
            $table->string('kind')->default('media');
            $table->text('description');
            $table->string('unit_code')->nullable();
            $table->decimal('quantity', 18, 3)->default(1);
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->decimal('line_total', 18, 2)->default(0);
            $table->timestampsTz();
        });

        Schema::create('bookings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->nullable()->index();
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
            $table->foreignUuid('product_id')->nullable()->index();
            $table->foreignUuid('placement_id')->nullable()->index();
            $table->foreignUuid('inventory_window_id')->nullable()->index();
            $table->decimal('quantity', 18, 3)->default(1);
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->timestampTz('starts_at')->nullable();
            $table->timestampTz('ends_at')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_lines');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('quote_lines');
        Schema::dropIfExists('quotes');
    }
};
