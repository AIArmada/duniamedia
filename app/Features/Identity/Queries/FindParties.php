<?php

declare(strict_types=1);

namespace App\Features\Identity\Queries;

use App\Features\Identity\Api\PartySummary;
use App\Features\Identity\Api\ResolvesParties;
use App\Features\Identity\Models\Party;

final readonly class FindParties implements ResolvesParties
{
    /**
     * @return array<string, PartySummary>
     */
    public function summariesByIds(string ...$ids): array
    {
        if ($ids === []) {
            return [];
        }

        return Party::query()
            ->with('organization')
            ->whereKey($ids)
            ->get()
            ->mapWithKeys(fn (Party $party): array => [$party->id => $this->summarise($party)])
            ->all();
    }

    /**
     * @return list<PartySummary>
     */
    public function byIds(string ...$ids): array
    {
        return array_values($this->summariesByIds(...$ids));
    }

    public function bySlug(string $slug): ?PartySummary
    {
        $party = Party::query()->with('organization')->where('slug', $slug)->first();

        return $party === null ? null : $this->summarise($party);
    }

    public function idForSlug(string $slug): ?string
    {
        return Party::query()->where('slug', $slug)->value('id');
    }

    public function displayName(string $id): ?string
    {
        return Party::query()->whereKey($id)->value('display_name');
    }

    public function logoUrl(string $id): ?string
    {
        $url = Party::query()->find($id)?->displayLogo();

        return $url === '' ? null : $url;
    }

    /**
     * @return list<string>
     */
    public function searchIds(string $like): array
    {
        return Party::query()
            ->where('display_name', 'like', $like)
            ->pluck('id')
            ->all();
    }

    private function summarise(Party $party): PartySummary
    {
        $logo = $party->displayLogo();

        return new PartySummary(
            id: $party->id,
            displayName: $party->display_name,
            slug: $party->slug,
            partyType: $party->party_type,
            sector: $party->organization?->organization_type ?? 'business',
            industry: $party->organization?->industry,
            logoUrl: $logo === '' ? null : $logo,
            notes: $party->notes,
        );
    }
}
