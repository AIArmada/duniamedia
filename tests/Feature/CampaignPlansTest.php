<?php

namespace Tests\Feature;

use AIArmada\Cart\Models\CartModel;
use App\Features\Identity\Models\Party;
use App\Features\Sales\EntryPoints\Livewire\CampaignPlanBuilder;
use App\Features\Sales\EntryPoints\Livewire\CampaignPlans;
use App\Features\Sales\EntryPoints\Livewire\QuoteBuilder;
use App\Features\Sales\Support\CampaignPlans as PlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesMediaOffers;
use Tests\TestCase;

class CampaignPlansTest extends TestCase
{
    use CreatesMediaOffers;
    use RefreshDatabase;

    public function test_a_user_can_keep_multiple_campaign_plans(): void
    {
        Livewire::test(CampaignPlans::class)
            ->set('name', 'Ramadan awareness')
            ->call('create');

        Livewire::test(CampaignPlans::class)
            ->set('name', 'Product launch')
            ->call('create');

        $this->assertDatabaseCount('carts', 2);
        $this->assertSame(1, CartModel::query()->where('metadata->name', 'Ramadan awareness')->count());
        $this->assertSame(2, CartModel::query()->count());
    }

    public function test_campaign_workspace_shows_saved_totals_and_filters_plans(): void
    {
        $offering = $this->createMediaOffer(['price' => 100000], [
            'price_from' => 1000,
            'pricing_mode' => 'fixed',
        ]);
        $plans = app(PlanService::class);
        $launch = $plans->create('Product launch', ['objective' => 'Awareness']);
        $plans->addOffering($launch, $offering);
        $plans->applyDiscount($launch, 'Package saving', '10%');
        $plans->create('Ramadan campaign');

        Livewire::test(CampaignPlans::class)
            ->assertSee('RM 900.00')
            ->assertSee('RM 100.00')
            ->set('sort', 'name')
            ->assertSeeInOrder(['Product launch', 'Ramadan campaign'])
            ->set('search', 'awareness')
            ->assertSee('Product launch')
            ->assertDontSee('Ramadan campaign')
            ->set('search', 'no-matching-campaign')
            ->assertSee('No plans match your search.')
            ->assertSee('RM 900.00')
            ->set('search', '')
            ->assertSee('Ramadan campaign');
    }

    public function test_media_offerings_and_package_discounts_update_the_plan_total(): void
    {
        $offering = $this->createMediaOffer([
            'price' => 100000,
        ], [
            'price_from' => 1000,
            'pricing_mode' => 'fixed',
        ]);

        $plans = app(PlanService::class);
        $plan = $plans->create('Discount test');

        Livewire::test(CampaignPlanBuilder::class, ['instance' => $plan->instance])
            ->set('offeringId', $offering->id)
            ->call('addOffering')
            ->set('discountName', 'Three-channel package')
            ->set('discountValue', '10%')
            ->call('applyDiscount');

        $summary = $plans->summary($plan->fresh());

        $this->assertSame(100000, $summary['subtotal']);
        $this->assertSame(10000, $summary['discount']);
        $this->assertSame(90000, $summary['total']);
        $this->assertSame(1, $summary['items']->count());
    }

    public function test_proposal_media_uses_the_same_draft_when_reopened(): void
    {
        $offering = $this->createMediaOffer(['price' => 100000], ['pricing_mode' => 'fixed']);

        $component = Livewire::test(QuoteBuilder::class)->call('add', $offering->id)->assertHasNoErrors();
        $instance = $component->get('planInstance');

        Livewire::test(QuoteBuilder::class)
            ->assertSet('planInstance', $instance)
            ->assertSee($offering->name);

        $this->assertDatabaseCount('carts', 1);
    }

    public function test_a_saved_plan_can_be_converted_into_a_quote_request(): void
    {
        Party::factory()->create(['slug' => 'ra-media-relations', 'display_name' => 'RA Media Relations Sdn Bhd']);

        $offering = $this->createMediaOffer([
            'price' => 100000,
        ], [
            'price_from' => 1000,
            'pricing_mode' => 'fixed',
        ]);
        $plans = app(PlanService::class);
        $plan = $plans->create('Quote conversion', ['objective' => 'Awareness']);
        $plans->addOffering($plan, $offering, 3);
        $plans->applyDiscount($plan, 'Package saving', '10%');

        $itemId = $plans->summary($plan)['items']->first()->id;

        Livewire::test(QuoteBuilder::class, ['plan' => $plan->instance])
            ->assertSee('RM 2,700.00')
            ->call('updateQuantity', $itemId, 4)
            ->assertHasNoErrors()
            ->assertSee('RM 3,600.00')
            ->call('updateQuantity', $itemId, 3)
            ->assertHasNoErrors()
            ->set('name', 'Test Buyer')
            ->set('email', 'buyer@example.com')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('quotes', ['status' => 'requested', 'subtotal_amount' => 3000]);
        $this->assertDatabaseHas('quote_lines', ['product_id' => $offering->id, 'quantity' => 3, 'unit_price' => 1000, 'line_total' => 3000]);
        $enquiry = \App\Features\Sales\Models\Enquiry::query()->firstOrFail();
        $this->assertSame(30000, $enquiry->metadata['campaign']['discount_minor']);
        $this->assertSame(270000, $enquiry->metadata['campaign']['total_minor']);
        $this->assertNotNull($plan->fresh()->checked_out_at);
        $this->assertCount(0, $plans->all());
    }
}
