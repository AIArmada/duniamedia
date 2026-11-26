<?php

declare(strict_types=1);

namespace App\Features\Creators\Queries;

use App\Features\Creators\Api\CreatorSummary;
use App\Features\Creators\Api\ResolvesCreators;
use App\Features\Creators\Models\CreatorProfile;

final readonly class FindCreator implements ResolvesCreators
{
    public function summary(string $creatorProfileId): ?CreatorSummary
    {
        $profile = CreatorProfile::query()->find($creatorProfileId);

        return $this->toSummary($profile);
    }

    public function publicSummaryBySlug(string $slug): ?CreatorSummary
    {
        return $this->toSummary(CreatorProfile::query()->publiclyListed()->where('slug', $slug)->first());
    }

    private function toSummary(?CreatorProfile $profile): ?CreatorSummary
    {
        return $profile === null ? null : new CreatorSummary(
            id: $profile->id,
            name: $profile->displayName(),
            slug: $profile->slug,
            niche: $profile->primary_niche,
            socialPlatform: $profile->social_platform,
            handle: $profile->handle,
            bio: $profile->bio,
            isFeatured: (bool) $profile->is_featured,
            imageUrl: $profile->displayImage(),
        );
    }
}
