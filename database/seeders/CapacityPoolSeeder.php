<?php

namespace Database\Seeders;

use App\Models\InventoryWindow;
use App\Support\CatalogueCapacity;
use Illuminate\Database\Seeder;

class CapacityPoolSeeder extends Seeder
{
    public function run(): void
    {
        $capacity = app(CatalogueCapacity::class);

        InventoryWindow::query()
            ->orderBy('starts_at')
            ->each(fn (InventoryWindow $window) => $capacity->ensurePool($window));
    }
}
