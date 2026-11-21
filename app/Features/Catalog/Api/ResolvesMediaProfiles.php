<?php

declare(strict_types=1);

namespace App\Features\Catalog\Api;

interface ResolvesMediaProfiles
{
    public function placementId(string $productId): ?string;

    public function inventoryWindowId(string $productId): ?string;

    public function medium(string $productId): ?string;
}
