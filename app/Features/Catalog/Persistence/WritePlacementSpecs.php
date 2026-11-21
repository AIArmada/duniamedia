<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\PlacementSpec;
use Illuminate\Database\Eloquent\Model;

final readonly class WritePlacementSpecs
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): PlacementSpec
    {
        return PlacementSpec::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): PlacementSpec
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): PlacementSpec
    {
        return PlacementSpec::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
