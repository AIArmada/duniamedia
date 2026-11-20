<?php

namespace Tests\Feature;

use AIArmada\Pricing\Models\PriceList;
use AIArmada\Products\Enums\ProductStatus;
use AIArmada\Products\Enums\ProductVisibility;
use AIArmada\Products\Models\Product;
use App\Models\CreatorProfile;
use App\Models\MediaProfile;
use App\Models\Party;
use Database\Seeders\MediaCatalogueSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaCatalogueTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogue_is_seeded_as_products_with_media_profiles(): void
    {
        foreach (range(1, 4) as $number) {
            $party = Party::query()->create([
                'party_type' => 'individual',
                'display_name' => 'Demo Creator '.$number,
                'slug' => 'demo-creator-'.$number,
                'status' => 'active',
            ]);

            CreatorProfile::query()->create([
                'creator_party_id' => $party->id,
                'creator_type' => 'creator',
                'slug' => 'demo-creator-'.$number,
                'verification_status' => 'verified',
                'status' => 'active',
            ]);
        }

        $this->seed(MediaCatalogueSeeder::class);

        $this->assertSame(53, Product::query()->count());
        $this->assertSame(53, MediaProfile::query()->count());
        $this->assertSame(4, MediaProfile::query()->whereNotNull('creator_profile_id')->count());
        $this->assertSame(32, MediaProfile::query()->where('pricing_mode', 'indicative')->count());
        $this->assertSame(21, MediaProfile::query()->where('pricing_mode', 'on_request')->count());
        $this->assertSame(1, PriceList::query()->where('slug', 'duniamedia-myr-2026')->count());

        $product = Product::query()->where('slug', '988-fm-2026-radio-digital')->firstOrFail();

        $this->assertSame(ProductStatus::Active, $product->status);
        $this->assertSame(ProductVisibility::CatalogSearch, $product->visibility);
        $this->assertSame(0, $product->price);
        $this->assertSame('30s spot', $product->mediaProfile->unit_code);
        $this->assertSame('100.00', $product->mediaProfile->price_from);
    }
}
