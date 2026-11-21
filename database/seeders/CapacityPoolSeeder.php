<?php

namespace Database\Seeders;

use App\Features\Inventory\Actions\EnsureCapacityPool;
use App\Features\Inventory\Models\InventoryWindow;
use Illuminate\Database\Seeder;

class CapacityPoolSeeder extends Seeder
{
    public function run(): void
    {
        $capacity = app(EnsureCapacityPool::class);

        InventoryWindow::query()
            ->orderBy('starts_at')
            ->each(fn (InventoryWindow $window) => $capacity->handle($window));
    }
}
