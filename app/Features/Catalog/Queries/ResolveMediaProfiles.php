<?php

declare(strict_types=1);

namespace App\Features\Catalog\Queries;

use App\Features\Catalog\Api\ResolvesMediaProfiles;
use App\Features\Catalog\Models\MediaProfile;

final readonly class ResolveMediaProfiles implements ResolvesMediaProfiles
{
    public function placementId(string $productId): ?string
    {
        return $this->column($productId, 'placement_id');
    }

    public function inventoryWindowId(string $productId): ?string
    {
        return $this->column($productId, 'inventory_window_id');
    }

    public function medium(string $productId): ?string
    {
        return $this->column($productId, 'medium');
    }

    private function column(string $productId, string $column): ?string
    {
        $value = MediaProfile::query()->where('product_id', $productId)->value($column);

        return $value === null ? null : (string) $value;
    }
}
