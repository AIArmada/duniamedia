<?php

declare(strict_types=1);

namespace App\Features\Clients\Queries;

use App\Features\Clients\Api\ListsClientParties;
use App\Features\Clients\Models\ClientReference;
use App\Features\Identity\Api\PartySummary;
use App\Features\Identity\Api\ResolvesParties;

final readonly class ListClientParties implements ListsClientParties
{
    public function __construct(private ResolvesParties $parties) {}

    /**
     * @return list<PartySummary>
     */
    public function clientParties(): array
    {
        $references = ClientReference::query()
            ->orderBy('sort_order')
            ->get()
            ->keyBy('client_party_id');

        $summaries = $this->parties->summariesByIds(...$references->keys()->all());

        return collect($summaries)
            ->map(function (PartySummary $party, string $id) use ($references): PartySummary {
                $label = $references[$id]->public_label;

                return $label === null ? $party : new PartySummary(
                    id: $party->id,
                    displayName: $label,
                    slug: $party->slug,
                    partyType: $party->partyType,
                    sector: $party->sector,
                    industry: $party->industry,
                    logoUrl: $party->logoUrl,
                    notes: $party->notes,
                );
            })
            ->sortBy(fn (PartySummary $party): string => $party->displayName)
            ->values()
            ->all();
    }
}
