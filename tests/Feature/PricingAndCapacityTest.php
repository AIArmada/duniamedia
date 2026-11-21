<?php

namespace Tests\Feature;

use AIArmada\Inventory\Data\ScopedCapacityLine;
use AIArmada\Inventory\Services\Stock\ScopedCapacityReservationService;
use AIArmada\Orders\Events\OrderCanceled;
use AIArmada\Orders\Models\Order;
use AIArmada\Pricing\Models\Price;
use AIArmada\Pricing\Models\PriceList;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Models\Placement;
use App\Features\Catalog\Support\CataloguePricing;
use App\Features\Identity\Models\Party;
use App\Features\Inventory\Actions\EnsureCapacityPool;
use App\Features\Inventory\Models\InventoryPool;
use App\Features\Inventory\Models\InventoryWindow;
use App\Features\Sales\EntryPoints\Livewire\QuoteBuilder;
use App\Features\Sales\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesMediaOffers;
use Tests\TestCase;

class PricingAndCapacityTest extends TestCase
{
    use CreatesMediaOffers;
    use RefreshDatabase;

    public function test_quote_uses_the_active_price_list(): void
    {
        Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'RA Media Relations Sdn Bhd',
            'slug' => 'ra-media-relations',
            'status' => 'active',
        ]);

        $product = $this->createMediaOffer([
            'name' => 'Priced Radio Spot',
            'slug' => 'priced-radio-spot',
        ], [
            'pricing_mode' => 'fixed',
            'price_from' => 1500,
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

        Livewire::test(QuoteBuilder::class)
            ->call('add', $product->id)
            ->set('name', 'Test Buyer')
            ->set('email', 'buyer@example.com')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors();

        $quote = Quote::query()->firstOrFail();

        $this->assertSame('1500.00', $quote->subtotal_amount);
        $this->assertSame('1500.00', $quote->lines()->firstOrFail()->unit_price);
        $this->assertTrue(app(CataloguePricing::class)->isCheckoutable($product->fresh()));
    }

    public function test_inventory_window_maps_to_a_reservable_capacity_pool(): void
    {
        $property = MediaProperty::query()->create([
            'property_type' => 'channel',
            'name' => 'Capacity test property',
            'slug' => 'capacity-test-property',
            'status' => 'active',
        ]);

        $placement = Placement::query()->create([
            'media_property_id' => $property->id,
            'placement_code' => 'capacity-test',
            'name' => 'Capacity test placement',
            'placement_type' => 'Outdoor',
            'status' => 'active',
        ]);

        $pool = InventoryPool::query()->create([
            'placement_id' => $placement->id,
            'unit_code' => 'month',
            'capacity_model' => 'exclusive',
            'base_capacity' => 2,
            'supply_status' => 'available',
        ]);

        $window = InventoryWindow::query()->create([
            'pool_id' => $pool->id,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'capacity' => 2,
            'reserved_quantity' => 0,
            'status' => 'open',
        ]);

        $capacity = app(EnsureCapacityPool::class);
        $capacity->handle($window);

        $service = app(ScopedCapacityReservationService::class);
        $line = new ScopedCapacityLine(
            $capacity->resourceKey($window),
            $capacity->scopeKey($window),
            1,
        );

        $this->assertSame(2, $service->available($line->resourceKey, $line->scopeKey));

        $service->reserve('capacity-test-hold', [$line], 600);
        $this->assertSame(1, $service->available($line->resourceKey, $line->scopeKey));

        $service->release('capacity-test-hold');
        $this->assertSame(2, $service->available($line->resourceKey, $line->scopeKey));

        $order = new Order(['order_number' => 'CAPACITY-CANCEL']);
        $service->reserve('order:CAPACITY-CANCEL', [$line], 600);
        $this->assertSame(1, $service->available($line->resourceKey, $line->scopeKey));

        event(new OrderCanceled($order, 'test cancellation'));
        $this->assertSame(2, $service->available($line->resourceKey, $line->scopeKey));
    }
}
