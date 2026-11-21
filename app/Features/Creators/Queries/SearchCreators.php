<?php

declare(strict_types=1);

namespace App\Features\Creators\Queries;

use App\Features\Creators\Api\CreatorSummary;
use App\Features\Creators\Api\ListsCreators;
use App\Features\Creators\Models\CreatorProfile;
use App\Features\Identity\Api\ResolvesParties;

final readonly class SearchCreators implements ListsCreators
{
    /**
     * @return list<CreatorSummary>
     */
    public function search(string $query, int $limit = 8): array
    {
        $like = '%'.$query.'%';

        return CreatorProfile::query()
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->where(function ($sub) use ($like): void {
                $sub->where('bio', 'like', $like)
                    ->orWhere('primary_niche', 'like', $like)
                    ->orWhere('social_platform', 'like', $like)
                    ->orWhereIn('creator_party_id', $this->partyIdsMatching($like));
            })
            ->orderByDesc('is_featured')
            ->limit($limit)
            ->get()
            ->map(fn (CreatorProfile $profile): CreatorSummary => new CreatorSummary(
                id: $profile->id,
                name: $profile->displayName(),
                slug: $profile->slug,
                niche: $profile->primary_niche,
                socialPlatform: $profile->social_platform,
                handle: $profile->handle,
                bio: $profile->bio,
                isFeatured: (bool) $profile->is_featured,
                imageUrl: $profile->displayImage(),
            ))
            ->all();
    }

    /**
     * @return list<string>
     */
    private function partyIdsMatching(string $like): array
    {
        return app(ResolvesParties::class)->searchIds($like);
    }

    /**
     * @return list<array{id: string, name: string}>
     */
    public function options(): array
    {
        return CreatorProfile::query()
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->orderBy('slug')
            ->get()
            ->map(fn (CreatorProfile $profile): array => ['id' => $profile->id, 'name' => $profile->displayName()])
            ->all();
    }
}
