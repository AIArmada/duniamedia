<?php

namespace App\Support;

use AIArmada\Inventory\Models\CapacityPool;
use App\Models\InventoryPool;
use App\Models\InventoryWindow;

class CatalogueCapacity
{
    public function resourceKey(InventoryWindow $window): string
    {
        $placementId = InventoryPool::query()->whereKey($window->pool_id)->value('placement_id');

        return 'placement:'.$placementId;
    }

    public function scopeKey(InventoryWindow $window): string
    {
        return 'window:'.$window->id;
    }

    public function ensurePool(InventoryWindow $window): CapacityPool
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
}
