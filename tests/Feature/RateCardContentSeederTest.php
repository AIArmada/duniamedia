<?php

namespace Tests\Feature;

use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Models\RateCardEntry;
use App\Features\Catalog\Models\RateCardSection;
use Database\Seeders\MediaCatalogueSeeder;
use Database\Seeders\RateCardContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateCardContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_canonical_deck_content_is_loaded_into_the_database(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardContentSeeder::class);

        $this->assertSame(38, RateCardSection::query()->distinct()->count('product_id'));
        $this->assertSame(388, RateCardSection::query()->count());
        $this->assertSame(2023, RateCardEntry::query()->whereNotNull('section_id')->count());
        $this->assertSame(0, RateCardEntry::query()->whereNull('section_id')->count());
        $this->assertSame(0, RateCardSection::query()->whereDoesntHave('channels')->count());
        $this->assertSame(0, RateCardSection::query()->whereDoesntHave('entries')->count());

        $tv3 = MediaProperty::query()->where('slug', 'media-prima-tv3')->firstOrFail();

        $this->assertTrue($tv3->rateCardSections()->exists());
        $this->assertGreaterThanOrEqual(3, $tv3->decks()->count());
    }
}
