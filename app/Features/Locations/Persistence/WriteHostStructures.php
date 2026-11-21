<?php

declare(strict_types=1);

namespace App\Features\Locations\Persistence;

use App\Features\Locations\Models\HostStructure;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteHostStructures
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): HostStructure
    {
        return HostStructure::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): HostStructure
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): HostStructure
    {
        return HostStructure::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
