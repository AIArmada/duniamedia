<?php

declare(strict_types=1);

namespace App\Features\Clients\Api;

interface ListsCampaigns
{
    /**
     * @return list<array{
     *     id: string, title: string, description: ?string, medium: ?string,
     *     channel: ?string, date: ?string, year: int, client_id: ?string
     * }>
     */
    public function search(string $query, int $limit = 8): array;

    /**
     * Recent published campaigns, newest first.
     *
     * @return list<array{
     *     id: string, title: string, description: ?string, medium: ?string,
     *     channel: ?string, date: ?string, year: int, client_id: ?string
     * }>
     */
    public function recent(int $limit = 12): array;

    /**
     * Campaigns keyed by id, labelled with their name.
     *
     * @return array<string, string>
     */
    public function options(): array;
}
