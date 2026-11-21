<?php

declare(strict_types=1);

namespace App\Features\Inventory;

use App\Features\Inventory\Api\ListsInventoryWindows;
use App\Features\Inventory\Api\ReservesCapacity;
use App\Features\Inventory\Queries\ListInventoryWindows;
use App\Features\Inventory\Queries\ReserveCapacity;
use App\Platform\FeatureModule;

final class FeatureServiceProvider extends FeatureModule
{
    public function register(): void
    {
        $this->app->bind(ReservesCapacity::class, ReserveCapacity::class);
        $this->app->bind(ListsInventoryWindows::class, ListInventoryWindows::class);
    }
}
