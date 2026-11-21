<?php

declare(strict_types=1);

namespace App\Features\Inventory\Api;

interface ListsInventoryWindows
{
    /**
     * @return array<string, string> id => human label
     */
    public function options(): array;
}
