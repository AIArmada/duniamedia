<?php

namespace Tests\Feature;

use App\Features\Catalog\EntryPoints\Livewire\RateFinder;
use App\Features\Catalog\Models\RateCardEntry;
use Livewire\Livewire;
use Database\Seeders\MediaCatalogueSeeder;
use Database\Seeders\RateCardContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateFinderTest extends TestCase
{
    use RefreshDatabase;

    public function test_rate_finder_renders_seeded_rate_lines(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardContentSeeder::class);

        $this->assertGreaterThan(0, RateCardEntry::query()->whereHas('rateSection.channels')->count());

        $this->get('/rates')
            ->assertOk()
            ->assertSee('Media Rate Exchange')
            ->assertSee('Add to plan');
    }
    public function test_rates_can_be_compared_across_searches_and_removed(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardContentSeeder::class);

        $entries = RateCardEntry::query()->whereHas('rateSection.product', fn ($query) => $query->where('status', 'active'))
            ->where('price_rm', '>', 0)->limit(5)->get();
        $this->assertCount(5, $entries);

        $component = Livewire::test(RateFinder::class)
            ->call('toggleComparison', $entries[0]->id)
            ->set('search', 'no-rate-matches-this-keyword')
            ->assertSee('No rates match those filters.')
            ->call('toggleComparison', $entries[1]->id)
            ->set('showComparison', true)
            ->assertSee('Compare placements')
            ->assertSee($entries[0]->item)
            ->assertSee($entries[1]->item)
            ->call('toggleComparison', $entries[2]->id)
            ->call('toggleComparison', $entries[3]->id)
            ->call('toggleComparison', $entries[4]->id)
            ->assertHasErrors('comparison')
            ->assertSet('comparison', $entries->take(4)->pluck('id')->all());

        $component->call('clearFilters')
            ->assertSet('comparison', $entries->take(4)->pluck('id')->all())
            ->call('toggleComparison', $entries[0]->id)
            ->assertSet('comparison', $entries->slice(1, 3)->pluck('id')->values()->all())
            ->call('clearComparison')
            ->assertSet('comparison', [])
            ->assertSet('showComparison', false);
    }
}
