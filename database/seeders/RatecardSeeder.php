<?php

namespace Database\Seeders;

use AIArmada\Addressing\Models\Address;
use AIArmada\Addressing\Models\AddressCountry;
use App\Models\AdFormat;
use App\Models\AssetSurface;
use App\Models\GeoMarket;
use App\Models\HostStructure;
use App\Models\InventoryPool;
use App\Models\InventoryWindow;
use App\Models\Location;
use App\Models\MediaAsset;
use App\Models\MediaOffering;
use App\Models\MediaProperty;
use App\Models\OfferingPlacement;
use App\Models\Organization;
use App\Models\Party;
use App\Models\PartyRole;
use App\Models\Placement;
use App\Models\PlacementFormat;
use App\Models\RateCard;
use App\Models\RateCardLine;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Str;

class RatecardSeeder extends Seeder
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

    private array $rateCards = [];

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

    private function importListing(array $item): MediaOffering
    {
        $seller = $this->sellerFor($item['media_owner'] ?? null);
        $property = $this->propertyFor($seller, $item);
        $slug = $item['slug'] ?? Str::slug($item['title']);
        $specs = $item['specifications'] ?? [];

        $placement = Placement::updateOrCreate(
            ['placement_code' => $slug],
            [
                'media_property_id' => $property->id,
                'asset_surface_id' => null,
                'name' => $item['title'],
                'placement_type' => $item['medium'],
                'status' => 'active',
            ],
        );

        $asset = MediaAsset::updateOrCreate(
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
            $location = Location::updateOrCreate(
                ['name' => $item['location'] ?? 'Nationwide'],
                [
                    'location_type' => $item['medium'] === 'Outdoor' ? 'site' : 'venue',
                    'venue_type' => $item['medium'] === 'Indoor' ? 'venue' : null,
                    'site_type' => $item['medium'] === 'Outdoor' ? 'outdoor' : null,
                    'geo_market_id' => $this->marketFor($item['location'] ?? 'Nationwide')->id,
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

            $structure = HostStructure::updateOrCreate(
                ['label' => $item['title'], 'location_id' => $location->id],
                ['owner_party_id' => $seller->id, 'structure_type' => $item['medium'], 'status' => 'active'],
            );

            $asset->update(['location_id' => $location->id, 'host_structure_id' => $structure->id]);

            $surface = AssetSurface::updateOrCreate(
                ['asset_id' => $asset->id, 'label' => 'Primary'],
                ['surface_type' => $item['medium']],
            );

            $placement->update(['asset_surface_id' => $surface->id, 'media_property_id' => null]);
        }

        $this->attachFormats($placement, $item['medium'], $specs['formats'] ?? []);

        $offering = MediaOffering::updateOrCreate(
            ['slug' => $slug],
            [
                'seller_party_id' => $seller->id,
                'name' => $item['title'],
                'sku' => $slug,
                'medium' => $item['medium'],
                'unit_code' => 'campaign',
                'summary' => $item['summary'],
                'description' => $item['description'] ?? null,
                'pricing_mode' => $item['pricing_mode'] ?? 'on_request',
                'price_from' => $item['price_from'] ?? null,
                'availability_status' => $item['availability_status'] ?? 'on_request',
                'channel_label' => $item['channel'] ?? null,
                'location_label' => $item['location'] ?? 'Nationwide',
                'audience_label' => $item['audience'] ?? null,
                'is_featured' => (bool) ($item['is_featured'] ?? false),
                'status' => 'published',
                'published_at' => now(),
                'specifications' => $specs,
            ],
        );

        $this->replaceMedia($offering, $item['image'] ?? null, 'image');

        OfferingPlacement::updateOrCreate(
            ['offering_id' => $offering->id, 'placement_id' => $placement->id],
            ['units_per_purchase' => 1],
        );
        $rateCard = $this->rateCardFor($seller);

        RateCardLine::updateOrCreate(
            ['rate_card_id' => $rateCard->id, 'offering_id' => $offering->id],
            [
                'pricing_model' => $item['pricing_mode'] ?? 'on_request',
                'unit_price' => $item['price_from'] ?? 0,
                'minimum_quantity' => 1,
            ],
        );

        $pool = InventoryPool::updateOrCreate(
            ['placement_id' => $placement->id, 'unit_code' => 'campaign'],
            [
                'capacity_model' => 'exclusive',
                'base_capacity' => 1,
                'supply_status' => $item['availability_status'] ?? 'on_request',
            ],
        );

        InventoryWindow::updateOrCreate(
            [
                'pool_id' => $pool->id,
                'starts_at' => now()->startOfYear(),
                'ends_at' => now()->endOfYear(),
            ],
            ['capacity' => 1, 'reserved_quantity' => 0, 'status' => 'open'],
        );

        $this->seedDocuments($offering, $specs['documents'] ?? []);

        return $offering;
    }

    private function sellerFor(?string $mediaOwner): Party
    {
        $needle = $this->normalize($mediaOwner ?? '');

        foreach (self::SELLER_MAP as $key => $slug) {
            if (str_contains($needle, $key)) {
                $party = Party::where('slug', $slug)->first();
                if ($party) {
                    return $party;
                }
            }
        }

        $slug = Str::slug($mediaOwner ?: 'independent-media-owner');

        $party = Party::updateOrCreate(
            ['slug' => $slug],
            ['party_type' => 'organization', 'display_name' => $mediaOwner ?: 'Independent media owner', 'status' => 'active'],
        );

        Organization::updateOrCreate(['party_id' => $party->id], ['organization_type' => 'media_owner', 'industry' => 'Media']);
        PartyRole::updateOrCreate(['party_id' => $party->id, 'role_code' => 'media_owner'], ['valid_from' => now()->startOfYear()]);

        return $party;
    }

    private function propertyFor(Party $seller, array $item): MediaProperty
    {
        $channel = $item['channel'] ?? $seller->display_name;
        $needle = $this->normalize($channel);

        $property = MediaProperty::where('owner_party_id', $seller->id)
            ->get()
            ->first(fn (MediaProperty $candidate): bool => str_contains($needle, $this->normalize($candidate->name)));

        if ($property) {
            return $property;
        }

        return MediaProperty::updateOrCreate(
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

    private function marketFor(string $label): GeoMarket
    {
        $slug = Str::slug($label);

        return GeoMarket::updateOrCreate(
            ['slug' => $slug],
            ['name' => $label, 'market_type' => 'market', 'country_code' => 'MY'],
        );
    }

    private function attachFormats(Placement $placement, string $type, array $formats): void
    {
        if ($formats === []) {
            $formats = match ($type) {
                'TV & Broadcast' => ['30-second TV spot', '15-second TV spot'],
                'Radio', 'Podcast' => ['Radio spot', 'Podcast integration'],
                'Print' => ['Print advertisement', 'Article'],
                'Digital' => ['Digital banner', 'Social post'],
                'Creators' => ['Social post', 'Social story'],
                default => ['Billboard face'],
            };
        }

        foreach (array_values(array_unique($formats)) as $index => $name) {
            $code = Str::slug($name);
            $creativeType = match (true) {
                str_contains($code, 'spot') => 'audio',
                str_contains($code, 'video') => 'video',
                str_contains($code, 'article') || str_contains($code, 'print') => 'print',
                default => 'display',
            };

            $format = AdFormat::updateOrCreate(
                ['format_code' => $code],
                ['name' => $name, 'creative_type' => $creativeType],
            );

            PlacementFormat::updateOrCreate(
                ['placement_id' => $placement->id, 'format_id' => $format->id],
                ['is_default' => $index === 0],
            );
        }
    }

    private function rateCardFor(Party $seller): RateCard
    {
        return $this->rateCards[$seller->id] ??= RateCard::firstOrCreate(
            ['seller_party_id' => $seller->id, 'effective_from' => now()->startOfYear()],
            [
                'name' => $seller->display_name.' Rate Card',
                'currency_code' => 'MYR',
                'status' => 'active',
            ],
        );
    }

    private function seedDocuments(MediaOffering $offering, array $documents): void
    {
        $offering->clearMediaCollection('documents');

        foreach ($documents as $index => $document) {
            $this->mediaFile(
                $offering,
                'ratecards/'.($document['file'] ?? ''),
                'documents',
                'local',
                [
                    'name' => $document['name'] ?? null,
                    'kind' => $document['kind'] ?? 'document',
                    'index' => $index,
                ],
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
