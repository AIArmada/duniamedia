<?php

namespace Tests\Feature;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\RateCardEntry;
use App\Features\Catalog\Models\RateCardNote;
use Database\Seeders\MediaCatalogueSeeder;
use Database\Seeders\RateCardEntrySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateCardEntrySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_extracted_rate_ledger_is_loaded_into_the_database(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardEntrySeeder::class);

        $this->assertSame(2023, RateCardEntry::query()->count());
        $this->assertSame(38, RateCardEntry::query()->distinct()->count('product_id'));

        $this->assertSame(107, RateCardNote::query()->where('kind', 'gap')->count());
        $this->assertSame(2, RateCardNote::query()->where('kind', 'missing_document')->count());

        $product = Product::query()->where('slug', '988-fm-2026-radio-digital')->firstOrFail();

        $this->assertDatabaseHas('rate_card_entries', [
            'product_id' => $product->id,
            'source_file' => '988-fm-2026-radio-digital.pdf',
            'page' => 1,
            'item' => 'ROS Central - Mon-Sun - casual',
            'price_rm' => 485,
        ]);

        $tvalhijrah = Product::query()->where('slug', 'tvalhijrah-bincang-niaga-2024')->firstOrFail();

        $this->assertSame(20, $tvalhijrah->mediaProfile->rateCardEntries()->count());
        $this->assertDatabaseHas('rate_card_entries', [
            'product_id' => $tvalhijrah->id,
            'section' => 'Berita AlHijrah package',
            'item' => '13 slot(s) - package cost',
            'price_rm' => 72000,
        ]);
    }
}
