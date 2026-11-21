<?php

declare(strict_types=1);

namespace App\Features\Inventory\Actions;

use AIArmada\Inventory\Models\CapacityPool;
use App\Features\Inventory\Models\InventoryPool;
use App\Features\Inventory\Models\InventoryWindow;

final readonly class EnsureCapacityPool
{
    public function handle(InventoryWindow $window): CapacityPool
    {
        return CapacityPool::query()->firstOrCreate(
            [
                'resource_key' => $this->resourceKey($window),
                'scope_key' => $this->scopeKey($window),
            ],
            [
                'capacity' => max(1, (int) $window->capacity),
            ],
        );
    }

    public function resourceKey(InventoryWindow $window): string
    {
        $placementId = InventoryPool::query()->whereKey($window->pool_id)->value('placement_id');

        return 'placement:'.$placementId;
    }

    public function scopeKey(InventoryWindow $window): string
    {
        return 'window:'.$window->id;
    }
}
