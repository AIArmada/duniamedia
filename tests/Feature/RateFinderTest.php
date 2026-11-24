<?php

namespace Tests\Feature;

use App\Features\Catalog\Models\RateCardEntry;
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
            ->assertSee('Search every')
            ->assertSee('rate lines');
    }
}
