<?php

declare(strict_types=1);

namespace App\Features\Inventory\Queries;

use App\Features\Inventory\Api\ListsInventoryWindows;
use App\Features\Inventory\Models\InventoryWindow;

final readonly class ListInventoryWindows implements ListsInventoryWindows
{
    /**
     * @return array<string, string>
     */
    public function options(): array
    {
        return InventoryWindow::query()
            ->orderBy('starts_at')
            ->get()
            ->mapWithKeys(function (InventoryWindow $window): array {
                $starts = $window->starts_at?->format('Y-m-d') ?? '?';
                $ends = $window->ends_at?->format('Y-m-d') ?? '?';

                return [$window->id => "{$starts} -> {$ends}"];
            })
            ->all();
    }
}
