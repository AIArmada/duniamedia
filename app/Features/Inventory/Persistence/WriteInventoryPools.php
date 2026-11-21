<?php

declare(strict_types=1);

namespace App\Features\Inventory\Persistence;

use App\Features\Inventory\Models\InventoryPool;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteInventoryPools
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): InventoryPool
    {
        return InventoryPool::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): InventoryPool
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): InventoryPool
    {
        return InventoryPool::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
