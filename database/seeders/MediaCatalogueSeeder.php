<?php

namespace Database\Seeders;

use AIArmada\Pricing\Models\PriceList;
use AIArmada\Products\Enums\ProductStatus;
use AIArmada\Products\Enums\ProductType;
use AIArmada\Products\Enums\ProductVisibility;
use AIArmada\Products\Models\Product;
use App\Features\Catalog\Actions\SaveMediaProfile;
use App\Features\Catalog\Models\Placement;
use App\Features\Creators\Models\CreatorProfile;
use App\Features\Identity\Models\Party;
use App\Features\Inventory\Models\InventoryPool;
use App\Features\Inventory\Models\InventoryWindow;
use Illuminate\Database\Seeder;

class MediaCatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $path = resource_path('data/media-catalogue-2026.json');

        if (! is_file($path)) {
            return;
        }

        $data = json_decode(file_get_contents($path), true);

        PriceList::updateOrCreate(
            ['slug' => 'duniamedia-myr-2026'],
            [
                'name' => 'Dunia Media 2026 — MYR',
                'currency' => 'MYR',
                'priority' => 10,
                'is_default' => true,
                'is_active' => true,
            ],
        );

        $partyIds = Party::query()->pluck('id', 'slug');
        $creatorIds = CreatorProfile::query()->pluck('id', 'slug');

        $unitOverrides = [
            'astro-video360-digital-2026' => 'package',
            'bernama-media-kit-2026' => 'package',
            'billboard-sarawak-smg-2026' => 'per month',
            'building-wall-activation' => 'package',
            'digital-video-advertising' => 'package',
            'drive-time-radio-placement' => '30s spot',
            'fmt-2026-digital-news' => 'package',
            'kl-sentral-station-sponsorship-2024' => 'package (12 months)',
            'klia-t1-airport-2024-premium' => 'annual',
            'klia-t2-airport-2024' => 'annual',
            'kuala-lumpur-led-billboard' => 'per month',
            'national-tv-brand-feature' => 'package',
            'podcast-brand-integration' => 'episode',
            'print-feature-advertorial' => 'per insertion',
            'putera-pre-post-2024-reference' => 'package',
            'ra-media-relations-live-tv-talkshow-2026-2027' => 'slot',
            'ra-media-website-media-solutions-2026' => 'package',
            'retail-mall-screen-network' => 'per month',
        ];

        foreach ($data['offerings'] ?? [] as $entry) {
            $product = Product::updateOrCreate(
                ['slug' => $entry['slug']],
                [
                    'name' => $entry['name'],
                    'sku' => $entry['sku'],
                    'description' => $entry['description'],
                    'short_description' => $entry['summary'],
                    'type' => ProductType::Service,
                    'status' => ProductStatus::Active,
                    'visibility' => ProductVisibility::CatalogSearch,
                    'price' => 0,
                    'currency' => 'MYR',
                    'is_featured' => $entry['is_featured'],
                    'is_taxable' => true,
                    'tax_class' => 'sst-8',
                    'requires_shipping' => false,
                    'supports_variants' => false,
                    'tracks_inventory' => false,
                    'published_at' => now(),
                    'metadata' => ['legacy_offering_slug' => $entry['slug']],
                ],
            );

            $placement = $entry['placement_code']
                ? Placement::query()->where('placement_code', $entry['placement_code'])->first()
                : null;

            $window = null;

            if ($placement) {
                $pool = InventoryPool::query()->where('placement_id', $placement->id)->first();
                $window = $pool
                    ? InventoryWindow::query()->where('pool_id', $pool->id)->orderBy('starts_at')->first()
                    : null;
            }

            app(SaveMediaProfile::class)->handle(
                $product->id,
                [
                    'media_owner_party_id' => $entry['media_owner_slug'] ? ($partyIds[$entry['media_owner_slug']] ?? null) : null,
                    'creator_profile_id' => $entry['creator_slug'] ? ($creatorIds[$entry['creator_slug']] ?? null) : null,
                    'medium' => $entry['medium'],
                    'unit_code' => $unitOverrides[$entry['slug']] ?? $entry['unit_code'],
                    'pricing_mode' => $entry['pricing_mode'],
                    'availability_status' => $entry['availability_status'],
                    'channel_label' => $entry['channel_label'],
                    'location_label' => $entry['location_label'],
                    'audience_label' => $entry['audience_label'],
                    'specifications' => $entry['specifications'] ?: null,
                    'verified_at' => $entry['verified_at'],
                    'sort_order' => $entry['sort_order'],
                    'show_rate_card' => true,
                    'terms' => $entry['terms'],
                    'price_from' => $entry['price_from'],
                    'placement_id' => $placement?->id,
                    'inventory_window_id' => $window?->id,
                ],
            );
            if (! app()->runningUnitTests()) {
                $product->clearMediaCollection('documents');

                foreach ($entry['documents'] ?? [] as $document) {
                    $file = storage_path('app/'.$document['path']);

                    if (! is_file($file) || strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'pdf') {
                        continue;
                    }

                    $product->addMedia($file)
                        ->preservingOriginal()
                        ->withCustomProperties([
                            'name' => $document['name'],
                            'kind' => $document['kind'],
                        ])
                        ->toMediaCollection('documents', 'media-private');
                }

                if ($entry['image_path'] && is_file(public_path($entry['image_path']))) {
                    $product->clearMediaCollection('hero');
                    $product->addMedia(public_path($entry['image_path']))
                        ->preservingOriginal()
                        ->toMediaCollection('hero');
                }
            }
        }
    }
}
