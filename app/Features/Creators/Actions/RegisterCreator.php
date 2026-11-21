<?php

declare(strict_types=1);

namespace App\Features\Creators\Actions;

use App\Features\Creators\Models\CreatorAccount;
use App\Features\Creators\Models\CreatorProfile;
use Illuminate\Support\Facades\DB;

final readonly class RegisterCreator
{
    /**
     * @param  array{
     *     creator_party_id: string,
     *     media_property_id: string,
     *     slug: string,
     *     primary_niche: string,
     *     social_platform: string,
     *     handle: string,
     *     bio?: string|null,
     *     managed_by_party_id?: string|null,
     *     followers?: int|null
     * }  $data
     */
    public function register(array $data): string
    {
        return DB::transaction(function () use ($data): string {
            $profile = CreatorProfile::query()->create([
                'creator_party_id' => $data['creator_party_id'],
                'creator_type' => 'creator',
                'slug' => $data['slug'],
                'primary_niche' => $data['primary_niche'],
                'social_platform' => $data['social_platform'],
                'handle' => $data['handle'],
                'bio' => $data['bio'] ?? null,
                'managed_by_party_id' => $data['managed_by_party_id'] ?? null,
                'country_code' => 'MY',
                'location_label' => 'Malaysia',
                'verification_status' => 'pending',
                'status' => 'pending',
            ]);

            CreatorAccount::query()->create([
                'creator_profile_id' => $profile->id,
                'media_property_id' => $data['media_property_id'],
                'handle' => $data['handle'],
                'followers' => $data['followers'] ?? null,
                'metrics_as_of' => now()->toDateString(),
            ]);

            return $profile->id;
        });
    }
}
