<?php

declare(strict_types=1);

namespace App\Features\Catalog\Api;

interface PresentsRateLines
{
    public function find(string $rateLineId): ?RateLineView;

    /** @return list<RateLineView> */
    public function preview(): array;

    /** @return list<RateLineView> */
    public function search(string $query, int $limit = 6): array;
}
