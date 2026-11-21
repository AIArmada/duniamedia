<?php

declare(strict_types=1);

namespace App\Features\Clients\Queries;

use App\Features\Clients\Api\ListsCampaigns;
use App\Features\Clients\Models\Campaign;
use App\Features\Identity\Api\ResolvesParties;

final readonly class SearchCampaigns implements ListsCampaigns
{
    public function __construct(private ResolvesParties $parties) {}

    /**
     * @return list<array{
     *     id: string, title: string, description: ?string, medium: ?string,
     *     channel: ?string, date: ?string, year: int, client_id: ?string
     * }>
     */
    public function search(string $query, int $limit = 8): array
    {
        $like = '%'.$query.'%';
        $clientIds = $this->parties->searchIds($like);

        return Campaign::query()
            ->published()
            ->with('service')
            ->where(function ($sub) use ($like, $clientIds): void {
                $sub->where('name', 'like', $like)
                    ->orWhere('summary', 'like', $like)
                    ->orWhere('medium', 'like', $like)
                    ->orWhere('channel_label', 'like', $like)
                    ->when($clientIds !== [], fn ($clientQuery) => $clientQuery->orWhereIn('client_party_id', $clientIds));
            })
            ->orderByDesc('starts_on')
            ->limit($limit)
            ->get()
            ->map(fn (Campaign $campaign): array => [
                'id' => $campaign->id,
                'title' => $campaign->name,
                'description' => $campaign->summary,
                'medium' => $campaign->medium,
                'channel' => $campaign->channel_label,
                'date' => $campaign->starts_on?->format('d M Y'),
                'year' => (int) $campaign->starts_on?->format('Y'),
                'client_id' => $campaign->client_party_id,
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function options(): array
    {
        return Campaign::query()->orderBy('name')->pluck('name', 'id')->all();
    }
}
