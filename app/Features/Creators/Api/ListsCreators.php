<?php

declare(strict_types=1);

namespace App\Features\Creators\Api;

interface ListsCreators
{
    /**
     * @return list<CreatorSummary>
     */
    public function search(string $query, int $limit = 8): array;

    /**
     * @return list<array{id: string, name: string}>
     */
    public function options(): array;
}
