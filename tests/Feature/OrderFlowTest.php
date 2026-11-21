<?php

namespace Tests\Feature;

use AIArmada\Inventory\Services\Stock\ScopedCapacityReservationService;
use AIArmada\Orders\Actions\CancelOrder;
use AIArmada\Orders\Models\Order;
use AIArmada\Pricing\Models\Price;
use AIArmada\Pricing\Models\PriceList;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Models\Placement;
use App\Features\Identity\Models\Party;
use App\Features\Identity\Models\PartyRole;
use App\Features\Inventory\Actions\EnsureCapacityPool;
use App\Features\Inventory\Models\InventoryPool;
use App\Features\Inventory\Models\InventoryWindow;
use App\Features\Sales\Actions\AcceptQuote;
use App\Features\Sales\Actions\RecordOrderPayment;
use App\Features\Sales\Models\Booking;
use App\Features\Sales\Models\Quote;
use App\Features\Sales\Models\QuoteLine;
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

        PartyRole::query()->create([
            'party_id' => $seller->id,
            'role_code' => 'seller',
            'valid_from' => now()->toDateString(),
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

        $order = app(AcceptQuote::class)->execute($quote->id, $seller->id);

        $this->assertSame(1, Order::query()->count());
        $this->assertSame(12000, $order->tax_total);
        $this->assertSame(162000, $order->grand_total);
        $this->assertSame($order->id, $quote->fresh()->order_id);
        $this->assertSame('accepted', $quote->fresh()->status);
        $this->assertSame(1, Booking::query()->count());
        $this->assertSame(1, $order->items()->count());

        $capacity = app(EnsureCapacityPool::class);
        $this->assertSame(0, app(ScopedCapacityReservationService::class)
            ->available($capacity->resourceKey($window), $capacity->scopeKey($window)));

        $same = app(AcceptQuote::class)->execute($quote->id, $seller->id);
        $this->assertSame($order->id, $same->id);
        $this->assertSame(1, Order::query()->count());

        app(RecordOrderPayment::class)->execute($order, 162000, 'manual', 'TEST-PAYMENT');

        $this->assertSame(1, $order->payments()->count());
        $this->assertSame('committed', app(ScopedCapacityReservationService::class)
            ->find('order:'.$order->order_number)->state);
    }

    public function test_cancelling_an_uncommitted_order_releases_the_held_capacity(): void
    {
        $this->seed(TaxSeeder::class);

        $seller = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'RA Media Relations Sdn Bhd',
            'slug' => 'ra-media-relations',
            'status' => 'active',
        ]);

        PartyRole::query()->create([
            'party_id' => $seller->id,
            'role_code' => 'seller',
            'valid_from' => now()->toDateString(),
        ]);

        $buyer = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'Cancel Buyer',
            'slug' => 'cancel-buyer',
            'status' => 'active',
        ]);

        $property = MediaProperty::query()->create([
            'property_type' => 'channel',
            'name' => 'Cancel property',
            'slug' => 'cancel-property',
            'status' => 'active',
        ]);

        $placement = Placement::query()->create([
            'media_property_id' => $property->id,
            'placement_code' => 'cancel-placement',
            'name' => 'Cancel placement',
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
            'name' => 'Cancel Billboard',
            'slug' => 'cancel-billboard',
        ], [
            'medium' => 'Outdoor',
            'pricing_mode' => 'fixed',
            'price_from' => 1500,
            'placement_id' => $placement->id,
            'inventory_window_id' => $window->id,
        ]);

        $quote = Quote::query()->create([
            'seller_party_id' => $seller->id,
            'buyer_party_id' => $buyer->id,
            'quote_number' => 'DM-CANCEL-TEST',
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

        $order = app(AcceptQuote::class)->execute($quote->id, $seller->id);

        $this->assertSame('held', app(ScopedCapacityReservationService::class)
            ->find('order:'.$order->order_number)->state);

        app(CancelOrder::class)->execute($order, 'Customer withdrew', $seller->id);

        $this->assertSame('released', app(ScopedCapacityReservationService::class)
            ->find('order:'.$order->order_number)->state);
    }
}
