<?php

namespace Tests\Feature;

use App\Features\Catalog\Models\RateCardEntry;
use App\Features\Catalog\Models\RateCardSection;
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
            ->whereHas('rateSection', fn ($query) => $query->where('key', '!=', RateCardSection::MEDIA_VALUE_KEY))
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

        $this->get(route('quote.builder', ['plan' => $instance]))
            ->assertOk()
            ->assertSee('Proposal Studio')
            ->assertSee($entry->item)
            ->assertSee('RM '.number_format((float) $entry->price_rm * 3, 2));

        $this->get('/rates?q='.urlencode($entry->item))
            ->assertOk()
            ->assertSee($entry->item);
    }

    public function test_hot_fm_media_value_is_not_a_purchasable_rate(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardContentSeeder::class);
        $reference = RateCardEntry::query()->whereHas('rateSection', fn ($query) => $query->where('key', RateCardSection::MEDIA_VALUE_KEY))->firstOrFail();
        $investment = RateCardEntry::query()->whereHas('rateSection', fn ($query) => $query->where('key', 'zoomerz-sponsorship-media-investment'))->firstOrFail();

        $preview = app(\App\Features\Catalog\Api\PresentsRateLines::class)->preview();
        $this->assertNotEmpty($preview);
        $homepage = $this->get('/')->assertOk();
        foreach ($preview as $line) {
            $homepage->assertSee(route('plans.rate.add', $line->id), false);
        }
        $homepage->assertDontSee(route('plans.rate.add', $reference->id), false);

        $this->get('/search?q='.urlencode($investment->item))
            ->assertOk()
            ->assertSee('Matching advertising rates.')
            ->assertSee($investment->item)
            ->assertSee(route('offers.show', 'hot-fm-zoomerz-on-ground'));

        foreach (['/media/owners/media-prima/hot-fm', '/media/channels/media-prima-hot-fm'] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Get heard.')
                ->assertSee('4.3 million')
                ->assertSee('KJ (Khairy Jamaluddin), Johan, Fara Fauzana & AG')
                ->assertSee('10am–3pm')
                ->assertSee('8pm–midnight')
                ->assertSee('Aidil Fikri (AI presenter)')
                ->assertSee('1,000 registrations')
                ->assertSee('10,000 loaves')
                ->assertSee('href="#hotfm-programme-bekpes-hot"', false)
                ->assertSee('Source: Media Prima Audio')
                ->assertSee('@hotfm976')
                ->assertSee('href="https://www.hotfm.audio/bekpes-hot-/" target="_blank" rel="noopener noreferrer"', false)
                ->assertSee('Official campaign story')
                ->assertSee('href="https://www.mediaprima.audio/', false)
                ->assertSee('href="https://www.instagram.com/hotfm976/', false)
                ->assertSee('href="https://www.tiktok.com/@hotfm976_', false)
                ->assertSee('href="https://www.youtube.com/channel/UCFV_yPFxPbbmrYJ9J6zJglA', false)
                ->assertSee('href="https://www.audioplus.audio/', false)
                ->assertSee('hotfm-show-art--hero', false)
                ->assertDontSee('https://assets.hotfm.audio/2026/07/featured_hurix_BekpesHot_hotfm2026.jpg', false)
                ->assertSee('RM 100,000')
                ->assertSee('RM 108,000')
                ->assertSee('Media value — reference only')
                ->assertSee(route('plans.rate.add', $investment->id), false)
                ->assertDontSee(route('plans.rate.add', $reference->id), false);
        }

        $this->get('/offers/hot-fm-zoomerz-on-ground')
            ->assertOk()
            ->assertDontSee(route('plans.rate.add', $reference->id), false);

        $this->get('/rates?q='.urlencode($reference->item))
            ->assertOk()
            ->assertDontSee(route('plans.rate.add', $reference->id), false);

        $this->withSession(['_token' => 'test-token']);
        $this->post(route('plans.rate.add', $reference->id), ['_token' => 'test-token'])->assertNotFound();
        $this->assertNull(session('campaign-plan-draft'));

        $this->post(route('plans.rate.add', $investment->id), ['_token' => 'test-token'])->assertRedirect();
        $plans = app(CampaignPlans::class);
        $plan = $plans->find((string) session('campaign-plan-draft'));
        $this->assertNotNull($plan);
        $this->assertSame(10000000, $plans->summary($plan)['subtotal']);
    }
}
