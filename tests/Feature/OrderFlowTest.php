<?php

namespace Tests\Feature;

use AIArmada\Inventory\Services\Stock\ScopedCapacityReservationService;
use AIArmada\Orders\Models\Order;
use AIArmada\Pricing\Models\Price;
use AIArmada\Pricing\Models\PriceList;
use App\Actions\AcceptQuote;
use App\Actions\RecordOrderPayment;
use App\Models\Booking;
use App\Models\InventoryPool;
use App\Models\InventoryWindow;
use App\Models\MediaProperty;
use App\Models\Party;
use App\Models\Placement;
use App\Models\Quote;
use App\Models\QuoteLine;
use App\Support\CatalogueCapacity;
use Database\Seeders\TaxSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesMediaOffers;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use CreatesMediaOffers;
    use RefreshDatabase;

    public function test_an_accepted_quote_becomes_one_order_and_a_booking(): void
    {
        $this->seed(TaxSeeder::class);

        $seller = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'RA Media Relations Sdn Bhd',
            'slug' => 'ra-media-relations',
            'status' => 'active',
        ]);

        $buyer = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'Test Buyer',
            'slug' => 'test-buyer',
            'status' => 'active',
        ]);

        $property = MediaProperty::query()->create([
            'property_type' => 'channel',
            'name' => 'Order test property',
            'slug' => 'order-test-property',
            'status' => 'active',
        ]);

        $placement = Placement::query()->create([
            'media_property_id' => $property->id,
            'placement_code' => 'order-test',
            'name' => 'Order test placement',
            'placement_type' => 'Outdoor',
            'status' => 'active',
        ]);

        $pool = InventoryPool::query()->create([
            'placement_id' => $placement->id,
            'unit_code' => 'month',
            'capacity_model' => 'exclusive',
            'base_capacity' => 1,
            'supply_status' => 'available',
        ]);

        $window = InventoryWindow::query()->create([
            'pool_id' => $pool->id,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'capacity' => 1,
            'reserved_quantity' => 0,
            'status' => 'open',
        ]);

        $product = $this->createMediaOffer([
            'name' => 'Order Test Billboard',
            'slug' => 'order-test-billboard',
        ], [
            'medium' => 'Outdoor',
            'pricing_mode' => 'fixed',
            'price_from' => 1500,
            'placement_id' => $placement->id,
            'inventory_window_id' => $window->id,
        ]);

        $list = PriceList::query()->create([
            'name' => 'Dunia Media 2026 — MYR',
            'slug' => 'duniamedia-myr-2026',
            'currency' => 'MYR',
            'priority' => 10,
            'is_default' => true,
            'is_active' => true,
        ]);

        Price::query()->create([
            'price_list_id' => $list->id,
            'priceable_type' => $product->getMorphClass(),
            'priceable_id' => $product->id,
            'amount' => 150000,
            'currency' => 'MYR',
            'min_quantity' => 1,
        ]);

        $quote = Quote::query()->create([
            'seller_party_id' => $seller->id,
            'buyer_party_id' => $buyer->id,
            'quote_number' => 'DM-ORDER-TEST',
            'currency_code' => 'MYR',
            'status' => 'requested',
            'price_visibility' => 'private',
            'valid_until' => now()->addDays(14)->toDateString(),
            'subtotal_amount' => 1500,
        ]);

        QuoteLine::query()->create([
            'quote_id' => $quote->id,
            'product_id' => $product->id,
            'kind' => 'media',
            'description' => $product->name,
            'quantity' => 1,
            'unit_price' => 1500,
            'line_total' => 1500,
        ]);

        $order = app(AcceptQuote::class)->execute($quote);

        $this->assertSame(1, Order::query()->count());
        $this->assertSame(12000, $order->tax_total);
        $this->assertSame(162000, $order->grand_total);
        $this->assertSame($order->id, $quote->fresh()->order_id);
        $this->assertSame('accepted', $quote->fresh()->status);
        $this->assertSame(1, Booking::query()->count());
        $this->assertSame(1, $order->items()->count());

        $capacity = app(CatalogueCapacity::class);
        $this->assertSame(0, app(ScopedCapacityReservationService::class)
            ->available($capacity->resourceKey($window), $capacity->scopeKey($window)));

        $same = app(AcceptQuote::class)->execute($quote->fresh());
        $this->assertSame($order->id, $same->id);
        $this->assertSame(1, Order::query()->count());

        app(RecordOrderPayment::class)->execute($order, 162000, 'manual', 'TEST-PAYMENT');

        $this->assertSame(1, $order->payments()->count());
        $this->assertSame('committed', app(ScopedCapacityReservationService::class)
            ->find('order:'.$order->order_number)->state);
    }
}
