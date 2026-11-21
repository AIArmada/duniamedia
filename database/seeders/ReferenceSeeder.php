<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Features\Catalog\Persistence\WriteAdFormats;
use App\Features\Catalog\Persistence\WriteDistributionPlatforms;
use App\Features\Catalog\Persistence\WriteMediaGroups;
use App\Features\Catalog\Persistence\WriteMediaProperties;
use App\Features\Catalog\Persistence\WriteMediaTypes;
use App\Features\Catalog\Persistence\WritePropertyDistributionPlatforms;
use App\Features\Catalog\Persistence\WritePropertyMediaTypes;
use App\Features\Identity\Persistence\WriteParties;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReferenceSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        $groups = app(WriteMediaGroups::class);
        $types = app(WriteMediaTypes::class);
        $platforms = app(WriteDistributionPlatforms::class);
        $formats = app(WriteAdFormats::class);

        $categories = [
            ['Broadcast', 'broadcast', 1],
            ['Audio', 'audio', 2],
            ['Out-of-home', 'out-of-home', 3],
            ['Digital', 'digital', 4],
            ['Print', 'print', 5],
            ['Creator', 'creator', 6],
            ['Events', 'events', 7],
        ];

        foreach ($categories as [$name, $slug, $sort]) {
            $groups->upsert(['slug' => $slug], ['name' => $name, 'sort_order' => $sort]);
        }

        $typeDefinitions = [
            ['TV & Broadcast', 'tv-broadcast', 'broadcast', 'broadcast', 1],
            ['Radio', 'radio', 'audio', 'radio', 2],
            ['Podcast', 'podcast', 'audio', 'podcast', 3],
            ['Outdoor', 'outdoor', 'out-of-home', 'outdoor', 4],
            ['Indoor', 'indoor', 'out-of-home', 'indoor', 5],
            ['Digital', 'digital', 'digital', 'digital', 6],
            ['Print', 'print', 'print', 'print', 7],
            ['Creators', 'creators', 'creator', 'creator', 8],
        ];

        foreach ($typeDefinitions as [$name, $slug, $category, $medium, $sort]) {
            $types->upsert(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'media_group_id' => $groups->findBySlug($category)?->id,
                    'medium_code' => $medium,
                    'sort_order' => $sort,
                ],
            );
        }

        foreach ([
            ['Television', 'television', 'broadcast'],
            ['Radio', 'radio', 'audio'],
            ['Website', 'website', 'digital'],
            ['Social media', 'social-media', 'social'],
            ['Out-of-home', 'out-of-home', 'outdoor'],
            ['Print', 'print', 'print'],
            ['Podcast', 'podcast', 'audio'],
            ['Event', 'event', 'events'],
            ['TikTok', 'tiktok', 'social'],
            ['Instagram', 'instagram', 'social'],
            ['YouTube', 'youtube', 'social'],
            ['Facebook', 'facebook', 'social'],
        ] as [$name, $slug, $kind]) {
            $platforms->upsert(['name' => $name], ['platform_kind' => $kind]);
        }

        foreach ([
            ['30-second TV spot', '30s-tv-spot', 'video', 30],
            ['15-second TV spot', '15s-tv-spot', 'video', 15],
            ['Radio spot', 'radio-spot', 'audio', 30],
            ['Podcast integration', 'podcast-integration', 'audio', null],
            ['Digital banner', 'digital-banner', 'display', null],
            ['Social post', 'social-post', 'display', null],
            ['Social story', 'social-story', 'display', 15],
            ['Article', 'article', 'copy', null],
            ['Billboard face', 'billboard-face', 'display', null],
            ['Elevator screen', 'elevator-screen', 'display', 15],
            ['Airport screen', 'airport-screen', 'display', 15],
            ['Transit wrap', 'transit-wrap', 'display', null],
            ['Print advertisement', 'print-advertisement', 'print', null],
        ] as [$name, $code, $creativeType, $duration]) {
            $formats->upsert(
                ['format_code' => $code],
                ['name' => $name, 'creative_type' => $creativeType, 'default_duration_seconds' => $duration],
            );
        }

        $this->seedMediaOwners();
    }

    private function seedMediaOwners(): void
    {
        $parties = app(WriteParties::class);
        $properties = app(WriteMediaProperties::class);
        $propertyTypes = app(WritePropertyMediaTypes::class);
        $propertyPlatforms = app(WritePropertyDistributionPlatforms::class);
        $types = app(WriteMediaTypes::class);
        $platforms = app(WriteDistributionPlatforms::class);

        $raMedia = $parties->party(
            ['slug' => 'ra-media-relations'],
            [
                'party_type' => 'organization',
                'display_name' => 'RA Media Relations Sdn Bhd',
                'status' => 'active',
                'website_url' => 'https://duniamedia.com',
            ],
        );

        $parties->organization(
            ['party_id' => $raMedia->id],
            ['organization_type' => 'media_agency', 'industry' => 'Media and advertising'],
        );

        $parties->role(
            ['party_id' => $raMedia->id, 'role_code' => 'seller'],
            ['valid_from' => now()->startOfYear()],
        );

        $ownerSlugs = [
            'Media Prima Berhad' => 'media-prima',
            'Astro Media Solutions' => 'astro',
            'Radio Televisyen Malaysia (RTM)' => 'rtm',
            'Al Hijrah Media Corporation' => 'al-hijrah',
            'Bernama' => 'bernama',
            'TVS (TV Sarawak)' => 'tvs',
            'Star Media Group' => 'star-media-group',
            'Karangkraf' => 'karangkraf',
            'Outdoor Media' => 'outdoor-media',
            'Cinema Ads' => 'cinema-ads',
            'KLIA' => 'klia',
            'KTM Advertising Space' => 'ktm',
            'Specialist media & events' => 'specialist-media',
        ];

        $typeMap = [
            'Television networks' => 'tv-broadcast',
            'Television' => 'tv-broadcast',
            'TV home shopping' => 'tv-broadcast',
            'Radio' => 'radio',
            'Digital publishing' => 'digital',
            'Digital' => 'digital',
            'Streaming' => 'digital',
            'Apps' => 'digital',
            'Out-of-home' => 'outdoor',
            'News and press' => 'print',
            'Celebrities and KOLs' => 'creators',
            'Events & experiences' => 'creators',
        ];

        $owners = require resource_path('data/media-owners.php');
        $owners[] = [
            'name' => 'Specialist media & events',
            'logo' => null,
            'mark' => 'SPECIALIST MEDIA',
            'accent' => '#176b95',
            'categories' => [
                ['label' => 'Digital', 'brands' => [
                    ['name' => 'BFM'], ['name' => 'FMT'], ['name' => 'The Malaysian Reserve'],
                    ['name' => 'The Edge'], ['name' => 'iMedia'], ['name' => 'Nusavue'],
                ]],
            ],
        ];

        foreach ($owners as $owner) {
            $ownerSlug = $ownerSlugs[$owner['name']] ?? Str::slug($owner['name']);
            $party = $parties->party(
                ['slug' => $ownerSlug],
                [
                    'party_type' => 'organization',
                    'display_name' => $owner['name'],
                    'status' => 'active',
                ],
            );

            $this->replaceMedia($party, isset($owner['logo']) ? 'assets/partners/'.$owner['logo'] : null, 'logo');

            $parties->organization(
                ['party_id' => $party->id],
                ['organization_type' => 'media_owner', 'industry' => 'Media'],
            );

            $parties->role(
                ['party_id' => $party->id, 'role_code' => 'media_owner'],
                ['valid_from' => now()->startOfYear()],
            );

            foreach ($owner['categories'] as $category) {
                foreach ($category['brands'] as $brand) {
                    $propertySlug = $ownerSlug.'-'.Str::slug($brand['name']);
                    $property = $properties->upsert(
                        ['slug' => $propertySlug],
                        [
                            'owner_party_id' => $party->id,
                            'property_type' => 'channel',
                            'name' => $brand['name'],
                            'status' => 'active',
                            'brand_mark' => $owner['mark'] ?? null,
                            'accent_color' => $owner['accent'] ?? null,
                        ],
                    );

                    $this->replaceMedia($property, isset($brand['logo']) ? 'assets/partners/'.$brand['logo'] : null, 'logo');

                    $typeSlug = $typeMap[$category['label']] ?? 'digital';
                    $mediaType = $types->findBySlug($typeSlug);

                    if ($mediaType) {
                        $propertyTypes->upsert(
                            ['media_property_id' => $property->id, 'media_type_id' => $mediaType->id],
                            [],
                        );
                    }

                    $platform = $platforms->findByKind($mediaType?->medium_code ?? 'digital');
                    if ($platform) {
                        $propertyPlatforms->upsert(
                            ['media_property_id' => $property->id, 'distribution_platform_id' => $platform->id],
                            ['url' => null, 'is_primary' => true],
                        );
                    }
                }
            }
        }
    }
}
