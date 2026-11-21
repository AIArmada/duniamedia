<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\PlacementFormat;
use Illuminate\Database\Eloquent\Model;

final readonly class WritePlacementFormats
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): PlacementFormat
    {
        return PlacementFormat::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): PlacementFormat
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): PlacementFormat
    {
        return PlacementFormat::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
