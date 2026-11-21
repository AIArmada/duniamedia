<?php

declare(strict_types=1);

namespace App\Features\Inventory\Persistence;

use App\Features\Inventory\Models\InventoryWindow;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteInventoryWindows
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): InventoryWindow
    {
        return InventoryWindow::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): InventoryWindow
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): InventoryWindow
    {
        return InventoryWindow::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
