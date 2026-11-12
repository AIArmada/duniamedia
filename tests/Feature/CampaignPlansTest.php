<?php

namespace Tests\Feature;

use AIArmada\Cart\Models\CartModel;
use App\Livewire\CampaignPlanBuilder;
use App\Livewire\CampaignPlans;
use App\Livewire\QuoteBuilder;
use App\Models\MediaOffering;
use App\Models\Party;
use App\Support\CampaignPlans as PlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignPlansTest extends TestCase
{
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

    public function test_media_offerings_and_package_discounts_update_the_plan_total(): void
    {
        $offering = MediaOffering::factory()->create([
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

    public function test_a_saved_plan_can_be_converted_into_a_quote_request(): void
    {
        Party::factory()->create(['slug' => 'ra-media-relations', 'display_name' => 'RA Media Relations Sdn Bhd']);

        $offering = MediaOffering::factory()->create(['price_from' => 1000, 'pricing_mode' => 'fixed']);
        $plans = app(PlanService::class);
        $plan = $plans->create('Quote conversion', ['objective' => 'Awareness']);
        $plans->addOffering($plan, $offering);

        Livewire::test(QuoteBuilder::class, ['plan' => $plan->instance])
            ->set('name', 'Test Buyer')
            ->set('email', 'buyer@example.com')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('quotes', ['status' => 'requested']);
        $this->assertNotNull($plan->fresh()->checked_out_at);
        $this->assertCount(0, $plans->all());
    }
}
