<?php

namespace Tests\Feature;

use App\Features\Catalog\Models\RateCardEntry;
use App\Features\Identity\Models\User;
use App\Features\Sales\Support\CampaignPlans;
use Database\Seeders\MediaCatalogueSeeder;
use Database\Seeders\RateCardContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatePlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_rate_line_can_be_added_to_a_plan_and_calculated(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardContentSeeder::class);

        $this->actingAs(User::query()->create([
            'name' => 'Plan Tester',
            'email' => 'plan-tester@example.test',
            'password' => 'password',
        ]));

        $this->withSession(['_token' => 'test-token']);

        $entry = RateCardEntry::query()
            ->whereNotNull('section_id')
            ->where('price_rm', '>', 0)
            ->firstOrFail();

        $this->post(route('plans.rate.add', $entry->id), ['_token' => 'test-token'])->assertRedirect();

        $plans = app(CampaignPlans::class);
        $instance = (string) session('campaign-plan-draft');

        $this->assertNotSame('', $instance);

        $plan = $plans->find($instance);

        $this->assertNotNull($plan);

        $summary = $plans->summary($plan);
        $item = $summary['items']->first();

        $this->assertNotNull($item);
        $this->assertSame((int) round((float) $entry->price_rm * 100), $item->price);
        $this->assertSame(1, $item->quantity);

        $plans->updateQuantity($plan, $item->id, 3);
        $summary = $plans->summary($plan);

        $this->assertSame(3, $summary['items']->first()->quantity);
        $this->assertSame((int) round((float) $entry->price_rm * 300), $summary['subtotal']);

        $this->get(route('plans.show', $instance))
            ->assertOk()
            ->assertSee($entry->item);

        $this->get('/rates?q='.urlencode($entry->item))
            ->assertOk()
            ->assertSee($entry->item);
    }
}
