<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Features\Catalog\Persistence\WriteDistributionPlatforms;
use App\Features\Catalog\Persistence\WriteMediaProperties;
use App\Features\Catalog\Persistence\WritePropertyDistributionPlatforms;
use App\Features\Creators\Persistence\WriteCreatorAccounts;
use App\Features\Creators\Persistence\WriteCreatorProfiles;
use App\Features\Identity\Persistence\WriteParties;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

class DrqisCreatorSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        $parties = app(WriteParties::class);
        $profiles = app(WriteCreatorProfiles::class);

        $party = $parties->findBySlug('drqis') ?? $parties->party(
            ['slug' => 'drqis'],
            ['party_type' => 'individual', 'display_name' => 'Dr Qistina', 'status' => 'active'],
        );

        $parties->role(
            ['party_id' => $party->id, 'role_code' => 'creator'],
            ['valid_from' => now()->startOfDay()],
        );

        // Preserve editorial changes and approval decisions on subsequent seed runs.
        $profile = $profiles->findBySlug('drqis') ?? $profiles->create([
            'creator_party_id' => $party->id,
            'creator_type' => 'creator',
            'slug' => 'drqis',
            'primary_niche' => 'Education',
            'social_platform' => 'TikTok',
            'handle' => '@drqis',
            'bio' => 'TikTok creator sharing educational content about oral health and everyday dental care.',
            'verification_status' => 'pending',
            'status' => 'pending',
            'pricing_mode' => 'on_request',
            'is_featured' => true,
        ]);

        $property = app(WriteMediaProperties::class)->upsert(
            ['slug' => 'drqis-tiktok'],
            [
                'owner_party_id' => $party->id,
                'property_type' => 'social_account',
                'name' => 'Dr Qistina — TikTok',
                'status' => 'active',
            ],
        );

        $platform = app(WriteDistributionPlatforms::class)->findByName('TikTok');

        if ($platform) {
            app(WritePropertyDistributionPlatforms::class)->upsert(
                ['media_property_id' => $property->id, 'distribution_platform_id' => $platform->id],
                ['handle' => '@drqis', 'is_primary' => true],
            );
        }

        app(WriteCreatorAccounts::class)->upsert(
            ['creator_profile_id' => $profile->id, 'media_property_id' => $property->id],
            ['handle' => '@drqis'],
        );

        if (! $profile->getFirstMedia('image')) {
            $this->mediaFile($profile, 'assets/creators/drqis/portrait.webp');
        }

        if ($profile->getMedia('gallery')->isEmpty()) {
            foreach (['portrait-02.webp', 'portrait-03.webp'] as $filename) {
                $this->mediaFile($profile, 'assets/creators/drqis/'.$filename, 'gallery');
            }
        }
    }
}
