<?php

declare(strict_types=1);

namespace Database\Seeders;

use AIArmada\Addressing\Features\AddressLifecycle\Models\Address;
use AIArmada\Addressing\Features\Geography\Models\AddressCountry;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Models\Placement;
use App\Features\Catalog\Persistence\WriteAdFormats;
use App\Features\Catalog\Persistence\WriteAssetSurfaces;
use App\Features\Catalog\Persistence\WriteMediaAssets;
use App\Features\Catalog\Persistence\WriteMediaProperties;
use App\Features\Catalog\Persistence\WritePlacementFormats;
use App\Features\Catalog\Persistence\WritePlacements;
use App\Features\Identity\Models\Party;
use App\Features\Identity\Persistence\WriteParties;
use App\Features\Inventory\Persistence\WriteInventoryPools;
use App\Features\Inventory\Persistence\WriteInventoryWindows;
use App\Features\Locations\Persistence\WriteGeoMarkets;
use App\Features\Locations\Persistence\WriteHostStructures;
use App\Features\Locations\Persistence\WriteLocations;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Str;

class CatalogueInfrastructureSeeder extends Seeder
{
    use SeedsMedia;

    private const RESTRICTED = [
        'astro-islamic-program-viewonly',
        'omnia-rr2027-master-deck-viewonly',
        'the-edge-fy2026-content-viewonly',
        'ciq-checkpoint-packages-viewonly',
        'impact-premium-ooh-2-3-viewonly',
        'kempen-ramadan-syawal-viewonly',
        'imedia-network-2025-26-blitz',
    ];

    private const SELLER_MAP = [
        'media prima' => 'media-prima',
        'astro' => 'astro',
        'rtm' => 'rtm',
        'alhijrah' => 'al-hijrah',
        'bernama' => 'bernama',
        'tvs' => 'tvs',
        'sarawak' => 'tvs',
        'star rfm' => 'star-media-group',
        'suria' => 'star-media-group',
        '988' => 'star-media-group',
        'karangkraf' => 'karangkraf',
        'sinar' => 'karangkraf',
        'big tree' => 'media-prima',
        'kl sentral' => 'ktm',
        'ktm' => 'ktm',
        'klia' => 'klia',
        'mahb' => 'klia',
        'bfm' => 'specialist-media',
        'fmt' => 'specialist-media',
        'malaysian reserve' => 'specialist-media',
        'imedia' => 'specialist-media',
        'malaysiakini' => 'specialist-media',
        'nusavue' => 'specialist-media',
        'target media' => 'specialist-media',
        'dbp' => 'specialist-media',
        'remedy' => 'specialist-media',
        'ra media' => 'ra-media-relations',
    ];

    public function run(): void
    {
        $path = resource_path('data/ratecards-2026.json');

        if (FileFacade::exists($path)) {
            $data = json_decode(FileFacade::get($path), true);

            foreach ($data['offerings'] ?? [] as $item) {
                if (in_array($item['slug'], self::RESTRICTED, true)) {
                    continue;
                }

                $this->importListing($item);
            }
        }

        foreach ($this->demoListings() as $item) {
            $this->importListing($item);
        }
    }

    private function importListing(array $item): void
    {
        $parties = app(WriteParties::class);
        $properties = app(WriteMediaProperties::class);
        $placements = app(WritePlacements::class);
        $assets = app(WriteMediaAssets::class);
        $surfaces = app(WriteAssetSurfaces::class);
        $locations = app(WriteLocations::class);
        $markets = app(WriteGeoMarkets::class);
        $hostStructures = app(WriteHostStructures::class);
        $formats = app(WriteAdFormats::class);
        $placementFormats = app(WritePlacementFormats::class);
        $pools = app(WriteInventoryPools::class);
        $windows = app(WriteInventoryWindows::class);

        $seller = $this->sellerFor($parties, $item['media_owner'] ?? null);
        $property = $this->propertyFor($properties, $seller, $item);
        $slug = $item['slug'] ?? Str::slug($item['title']);
        $specs = $item['specifications'] ?? [];

        $placement = $placements->upsert(
            ['placement_code' => $slug],
            [
                'media_property_id' => $property->id,
                'asset_surface_id' => null,
                'name' => $item['title'],
                'placement_type' => $item['medium'],
                'status' => 'active',
            ],
        );

        $asset = $assets->upsert(
            ['asset_code' => $slug],
            [
                'media_property_id' => $property->id,
                'asset_type' => $item['medium'],
                'name' => $item['title'],
                'status' => 'active',
                'specs' => $specs,
            ],
        );

        $this->replaceMedia($asset, $item['image'] ?? null, 'image');

        if (in_array($item['medium'], ['Outdoor', 'Indoor'], true)) {
            $market = $markets->upsert(
                ['slug' => Str::slug($item['location'] ?? 'Nationwide')],
                ['name' => $item['location'] ?? 'Nationwide', 'market_type' => 'market', 'country_code' => 'MY'],
            );

            $location = $locations->upsert(
                ['name' => $item['location'] ?? 'Nationwide'],
                [
                    'location_type' => $item['medium'] === 'Outdoor' ? 'site' : 'venue',
                    'venue_type' => $item['medium'] === 'Indoor' ? 'venue' : null,
                    'site_type' => $item['medium'] === 'Outdoor' ? 'outdoor' : null,
                    'geo_market_id' => $market->id,
                    'is_public' => true,
                    'status' => 'active',
                ],
            );

            $address = Address::updateOrCreate(
                ['label' => $item['location'] ?? 'Nationwide'],
                [
                    'country_id' => AddressCountry::where('iso2', 'MY')->value('id'),
                    'city' => $item['location'] ?? null,
                    'state' => $this->stateFor($item['location'] ?? ''),
                    'country' => 'Malaysia',
                    'country_code' => 'MY',
                ],
            );

            $location->attachAddress($address, 'primary', true);

            $structure = $hostStructures->upsert(
                ['label' => $item['title'], 'location_id' => $location->id],
                ['owner_party_id' => $seller->id, 'structure_type' => $item['medium'], 'status' => 'active'],
            );

            $assets->update($asset, ['location_id' => $location->id, 'host_structure_id' => $structure->id]);

            $surface = $surfaces->upsert(
                ['asset_id' => $asset->id, 'label' => 'Primary'],
                ['surface_type' => $item['medium']],
            );

            $placements->update($placement, ['asset_surface_id' => $surface->id, 'media_property_id' => null]);
        }

        $this->attachFormats($formats, $placementFormats, $placement, $item['medium'], $specs['formats'] ?? []);

        $pool = $pools->upsert(
            ['placement_id' => $placement->id, 'unit_code' => 'campaign'],
            [
                'capacity_model' => 'exclusive',
                'base_capacity' => 1,
                'supply_status' => $item['availability_status'] ?? 'on_request',
            ],
        );

        $windows->upsert(
            [
                'pool_id' => $pool->id,
                'starts_at' => now()->startOfYear(),
                'ends_at' => now()->endOfYear(),
            ],
            ['capacity' => 1, 'reserved_quantity' => 0, 'status' => 'open'],
        );
    }

    private function sellerFor(WriteParties $parties, ?string $mediaOwner): Party
    {
        $needle = $this->normalize($mediaOwner ?? '');

        foreach (self::SELLER_MAP as $key => $slug) {
            if (str_contains($needle, (string) $key) && ($party = $parties->findBySlug($slug)) !== null) {
                return $party;
            }
        }

        $slug = Str::slug($mediaOwner ?: 'independent-media-owner');

        $party = $parties->party(
            ['slug' => $slug],
            ['party_type' => 'organization', 'display_name' => $mediaOwner ?: 'Independent media owner', 'status' => 'active'],
        );

        $parties->organization(['party_id' => $party->id], ['organization_type' => 'media_owner', 'industry' => 'Media']);
        $parties->role(['party_id' => $party->id, 'role_code' => 'media_owner'], ['valid_from' => now()->startOfYear()]);

        return $party;
    }

    private function propertyFor(WriteMediaProperties $properties, Party $seller, array $item): MediaProperty
    {
        $channel = $item['channel'] ?? $seller->display_name;
        $needle = $this->normalize($channel);

        $property = $properties->byOwner($seller->id)
            ->first(fn ($candidate): bool => str_contains($needle, $this->normalize($candidate->name)));

        if ($property) {
            return $property;
        }

        return $properties->upsert(
            ['slug' => $seller->slug.'-'.Str::slug($channel)],
            [
                'owner_party_id' => $seller->id,
                'property_type' => 'channel',
                'name' => $channel,
                'status' => 'active',
            ],
        );
    }

    private function stateFor(string $location): ?string
    {
        if (str_contains($location, '+')) {
            return null;
        }

        return match (true) {
            str_contains($location, 'Sarawak') => 'Sarawak',
            str_contains($location, 'Penang') => 'Pulau Pinang',
            str_contains($location, 'Johor') => 'Johor',
            str_contains($location, 'Klang'), str_contains($location, 'Sepang') => 'Selangor',
            default => null,
        };
    }

    private function attachFormats(WriteAdFormats $formats, WritePlacementFormats $placementFormats, Placement $placement, string $type, array $listingFormats): void
    {
        if ($listingFormats === []) {
            $listingFormats = match ($type) {
                'TV & Broadcast' => ['30-second TV spot', '15-second TV spot'],
                'Radio', 'Podcast' => ['Radio spot', 'Podcast integration'],
                'Print' => ['Print advertisement', 'Article'],
                'Digital' => ['Digital banner', 'Social post'],
                'Creators' => ['Social post', 'Social story'],
                default => ['Billboard face'],
            };
        }

        foreach (array_values(array_unique($listingFormats)) as $index => $name) {
            $code = Str::slug($name);
            $creativeType = match (true) {
                str_contains($code, 'spot') => 'audio',
                str_contains($code, 'video') => 'video',
                str_contains($code, 'article') || str_contains($code, 'print') => 'print',
                default => 'display',
            };

            $format = $formats->upsert(
                ['format_code' => $code],
                ['name' => $name, 'creative_type' => $creativeType],
            );

            $placementFormats->upsert(
                ['placement_id' => $placement->id, 'format_id' => $format->id],
                ['is_default' => $index === 0],
            );
        }
    }

    private function normalize(?string $value): string
    {
        return Str::lower(Str::ascii($value ?? ''));
    }

    private function demoListings(): array
    {
        return [
            ['title' => 'Kuala Lumpur LED Billboard', 'medium' => 'Outdoor', 'channel' => null, 'media_owner' => 'RA Media Relations', 'location' => 'Klang Valley', 'audience' => 'Urban commuters', 'summary' => 'High-impact digital roadside creative format.', 'image' => 'assets/img/offering-outdoor.webp'],
            ['title' => 'National TV Brand Feature', 'medium' => 'TV & Broadcast', 'channel' => null, 'media_owner' => 'RA Media Relations', 'location' => 'Nationwide', 'audience' => 'Mass-market households', 'summary' => 'Brand storytelling and broadcast feature opportunities.', 'image' => 'assets/img/offering-tv.webp'],
            ['title' => 'Drive-Time Radio Placement', 'medium' => 'Radio', 'channel' => null, 'media_owner' => 'RA Media Relations', 'location' => 'Nationwide', 'audience' => 'Daily commuters', 'summary' => 'Radio campaigns and audience-focused slots.', 'image' => 'assets/img/offering-radio.webp'],
            ['title' => 'Retail Mall Screen Network', 'medium' => 'Indoor', 'channel' => null, 'media_owner' => 'RA Media Relations', 'location' => 'Klang Valley', 'audience' => 'Retail shoppers', 'summary' => 'Digital screen media across shopping environments.', 'image' => 'assets/img/offering-indoor.webp'],
            ['title' => 'Niche Creator Campaign', 'medium' => 'Creators', 'channel' => null, 'media_owner' => 'RA Media Relations', 'location' => 'Nationwide', 'audience' => 'Targeted interest communities', 'summary' => 'Creator-led storytelling matched to audience interests.', 'image' => 'assets/img/offering-creators.webp'],
            ['title' => 'Digital Video Advertising', 'medium' => 'Digital', 'channel' => null, 'media_owner' => 'RA Media Relations', 'location' => 'Nationwide', 'audience' => 'Digital-first consumers', 'summary' => 'Cross-platform video and digital campaign placements.', 'image' => 'assets/img/offering-digital.webp'],
            ['title' => 'Print Feature & Advertorial', 'medium' => 'Print', 'channel' => null, 'media_owner' => 'RA Media Relations', 'location' => 'Nationwide', 'audience' => 'Editorial readers', 'summary' => 'Editorial-style and standard print advertising options.', 'image' => 'assets/img/print.webp'],
            ['title' => 'Podcast Brand Integration', 'medium' => 'Podcast', 'channel' => null, 'media_owner' => 'RA Media Relations', 'location' => 'Nationwide', 'audience' => 'Focused interest groups', 'summary' => 'Host-led conversations and sponsorship opportunities.', 'image' => 'assets/img/radio.webp'],
            ['title' => 'Building Wall Activation', 'medium' => 'Outdoor', 'channel' => null, 'media_owner' => 'RA Media Relations', 'location' => 'Klang Valley', 'audience' => 'Local high-footfall audience', 'summary' => 'Custom-format wall media subject to site rights and approvals.', 'image' => 'assets/img/custom-media.webp'],
        ];
    }
}
