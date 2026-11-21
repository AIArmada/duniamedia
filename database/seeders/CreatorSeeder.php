<?php

declare(strict_types=1);

namespace Database\Seeders;

use AIArmada\Persons\Models\Person;
use App\Features\Catalog\Persistence\WriteDistributionPlatforms;
use App\Features\Catalog\Persistence\WriteMediaProperties;
use App\Features\Catalog\Persistence\WritePlacements;
use App\Features\Catalog\Persistence\WritePropertyDistributionPlatforms;
use App\Features\Creators\Persistence\WriteCreatorAccounts;
use App\Features\Creators\Persistence\WriteCreatorDeliveryTemplates;
use App\Features\Creators\Persistence\WriteCreatorProfiles;
use App\Features\Identity\Persistence\WriteParties;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

class CreatorSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        $parties = app(WriteParties::class);
        $profiles = app(WriteCreatorProfiles::class);
        $properties = app(WriteMediaProperties::class);
        $propertyPlatforms = app(WritePropertyDistributionPlatforms::class);
        $platforms = app(WriteDistributionPlatforms::class);
        $accounts = app(WriteCreatorAccounts::class);
        $placements = app(WritePlacements::class);
        $templates = app(WriteCreatorDeliveryTemplates::class);

        $seller = $parties->findBySlug('ra-media-relations')
            ?? throw new \RuntimeException('The RA Media seller party must be seeded before creators.');

        $creators = [
            ['Travel & Local Discovery', 'Travel', 'TikTok', 'creators', 'Travel and local discovery creator covering destinations and experiences across Malaysia.'],
            ['Food & Lifestyle', 'Food', 'Instagram', 'creator-feature', 'Food and lifestyle creator focused on everyday dining, recipes and local favourites.'],
            ['Technology & Productivity', 'Technology', 'YouTube', 'digital', 'Technology creator covering productivity tools, devices and practical workflows.'],
            ['Home & Family', 'Family', 'TikTok', 'demo-creator-home', 'Family and home creator sharing practical ideas for Malaysian households.'],
        ];

        foreach ($creators as $index => [$niche, $category, $platformName, $image, $bio]) {
            $number = $index + 1;
            $slug = 'demo-creator-'.$number;
            $handle = '@demo_creator_'.$number;

            $party = $parties->party(
                ['slug' => $slug],
                ['party_type' => 'individual', 'display_name' => 'Demo Creator '.$number, 'status' => 'active'],
            );

            $person = Person::updateOrCreate(
                ['slug' => $slug.'-person'],
                ['name' => 'Demo Creator '.$number, 'bio' => ['en' => $bio], 'status' => 'active'],
            );

            $parties->updateParty($party, ['person_id' => $person->id]);

            $parties->role(
                ['party_id' => $party->id, 'role_code' => 'creator'],
                ['valid_from' => now()->startOfYear()],
            );

            $profile = $profiles->upsert(
                ['slug' => $slug],
                [
                    'creator_party_id' => $party->id,
                    'creator_type' => 'creator',
                    'primary_niche' => $category,
                    'social_platform' => $platformName,
                    'handle' => $handle,
                    'bio' => $bio,
                    'managed_by_party_id' => $seller->id,
                    'country_code' => 'MY',
                    'location_label' => 'Malaysia',
                    'verification_status' => 'verified',
                    'status' => 'active',
                    'verified_at' => now(),
                    'is_featured' => true,
                    'pricing_mode' => 'on_request',
                ],
            );

            $this->replaceMedia($profile, 'assets/img/'.$image.'.webp', 'image');

            $property = $properties->upsert(
                ['slug' => $slug.'-'.strtolower($platformName)],
                [
                    'owner_party_id' => $party->id,
                    'property_type' => 'social_account',
                    'name' => 'Demo Creator '.$number.' — '.$platformName,
                    'status' => 'active',
                ],
            );

            $platform = $platforms->findByName($platformName);

            if ($platform) {
                $propertyPlatforms->upsert(
                    ['media_property_id' => $property->id, 'distribution_platform_id' => $platform->id],
                    ['handle' => $handle, 'is_primary' => true],
                );
            }

            $accounts->upsert(
                ['creator_profile_id' => $profile->id, 'media_property_id' => $property->id],
                [
                    'handle' => $handle,
                    'followers' => 50000 * $number,
                    'average_views' => 12000 * $number,
                    'engagement_rate' => 4.5,
                    'metrics_as_of' => now()->toDateString(),
                ],
            );

            $placement = $placements->upsert(
                ['placement_code' => $slug.'-post'],
                [
                    'media_property_id' => $property->id,
                    'name' => 'Demo Creator '.$number.' social post',
                    'placement_type' => 'Creators',
                    'status' => 'active',
                ],
            );

            $templates->upsert(
                ['creator_profile_id' => $profile->id, 'placement_id' => $placement->id],
                ['quantity' => 1, 'usage_rights_days' => 30, 'exclusivity_days' => 0, 'notes' => 'One sponsored post.'],
            );
        }
    }
}
