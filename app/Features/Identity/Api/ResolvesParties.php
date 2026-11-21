<?php

declare(strict_types=1);

namespace App\Features\Identity\Api;

interface ResolvesParties
{
    /**
     * @return array<string, PartySummary> keyed by party id
     */
    public function summariesByIds(string ...$ids): array;

    /**
     * @return list<PartySummary>
     */
    public function byIds(string ...$ids): array;

    public function bySlug(string $slug): ?PartySummary;

    public function idForSlug(string $slug): ?string;

    public function displayName(string $id): ?string;

    public function logoUrl(string $id): ?string;

    /**
     * Party ids whose display name matches a LIKE pattern.
     *
     * @return list<string>
     */
    public function searchIds(string $like): array;
}
