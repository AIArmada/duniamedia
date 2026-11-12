<?php

namespace Database\Seeders;

use AIArmada\Persons\Models\Person;
use App\Models\CreatorAccount;
use App\Models\CreatorDeliveryTemplate;
use App\Models\CreatorProfile;
use App\Models\MediaOffering;
use App\Models\MediaProperty;
use App\Models\OfferingPlacement;
use App\Models\Party;
use App\Models\PartyRole;
use App\Models\Placement;
use App\Models\Platform;
use App\Models\PropertyPlatform;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

class CreatorSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        $seller = Party::where('slug', 'ra-media-relations')->firstOrFail();

        $creators = [
            ['Travel & Local Discovery', 'Travel', 'TikTok', 'creators', 'Travel and local discovery creator covering destinations and experiences across Malaysia.'],
            ['Food & Lifestyle', 'Food', 'Instagram', 'creator-feature', 'Food and lifestyle creator focused on everyday dining, recipes and local favourites.'],
            ['Technology & Productivity', 'Technology', 'YouTube', 'digital', 'Technology creator covering productivity tools, devices and practical workflows.'],
            ['Home & Family', 'Family', 'TikTok', 'indoor', 'Family and home creator sharing practical ideas for Malaysian households.'],
        ];

        foreach ($creators as $index => [$niche, $category, $platformName, $image, $bio]) {
            $number = $index + 1;
            $slug = 'demo-creator-'.$number;
            $handle = '@demo_creator_'.$number;

            $party = Party::updateOrCreate(
                ['slug' => $slug],
                ['party_type' => 'individual', 'display_name' => 'Demo Creator '.$number, 'status' => 'active'],
            );

            $person = Person::updateOrCreate(
                ['slug' => $slug.'-person'],
                ['name' => 'Demo Creator '.$number, 'bio' => ['en' => $bio], 'status' => 'active'],
            );

            $party->update(['person_id' => $person->id]);

            PartyRole::updateOrCreate(
                ['party_id' => $party->id, 'role_code' => 'creator'],
                ['valid_from' => now()->startOfYear()],
            );

            $profile = CreatorProfile::updateOrCreate(
                ['slug' => $slug],
                [
                    'creator_party_id' => $party->id,
                    'creator_type' => 'creator',
                    'primary_niche' => $category,
                    'platform_label' => $platformName,
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

            $property = MediaProperty::updateOrCreate(
                ['slug' => $slug.'-'.strtolower($platformName)],
                [
                    'owner_party_id' => $party->id,
                    'property_type' => 'social_account',
                    'name' => 'Demo Creator '.$number.' — '.$platformName,
                    'status' => 'active',
                ],
            );

            $platform = Platform::where('name', $platformName)->first();

            if ($platform) {
                PropertyPlatform::updateOrCreate(
                    ['media_property_id' => $property->id, 'platform_id' => $platform->id],
                    ['handle' => $handle, 'is_primary' => true],
                );
            }

            CreatorAccount::updateOrCreate(
                ['creator_profile_id' => $profile->id, 'media_property_id' => $property->id],
                [
                    'handle' => $handle,
                    'followers' => 50000 * $number,
                    'average_views' => 12000 * $number,
                    'engagement_rate' => 4.5,
                    'metrics_as_of' => now()->toDateString(),
                ],
            );

            $placement = Placement::updateOrCreate(
                ['placement_code' => $slug.'-post'],
                [
                    'media_property_id' => $property->id,
                    'name' => 'Demo Creator '.$number.' social post',
                    'placement_type' => 'Creators',
                    'status' => 'active',
                ],
            );

            $offering = MediaOffering::updateOrCreate(
                ['slug' => $slug],
                [
                    'seller_party_id' => $seller->id,
                    'creator_profile_id' => $profile->id,
                    'name' => 'Demo Creator '.$number,
                    'sku' => $slug,
                    'medium' => 'Creators',
                    'unit_code' => 'post',
                    'summary' => $bio,
                    'description' => 'Illustrative creator profile only. Not a real creator or verified partnership.',
                    'pricing_mode' => 'on_request',
                    'availability_status' => 'on_request',
                    'channel_label' => $platformName,
                    'location_label' => 'Malaysia',
                    'audience_label' => $niche,
                    'is_featured' => true,
                    'status' => 'published',
                    'published_at' => now(),
                ],
            );

            $this->replaceMedia($offering, 'assets/img/'.$image.'.webp', 'image');

            OfferingPlacement::updateOrCreate(
                ['offering_id' => $offering->id, 'placement_id' => $placement->id],
                ['units_per_purchase' => 1],
            );

            CreatorDeliveryTemplate::updateOrCreate(
                ['creator_profile_id' => $profile->id, 'placement_id' => $placement->id],
                ['quantity' => 1, 'usage_rights_days' => 30, 'exclusivity_days' => 0, 'notes' => 'One sponsored post.'],
            );
        }
    }
}
