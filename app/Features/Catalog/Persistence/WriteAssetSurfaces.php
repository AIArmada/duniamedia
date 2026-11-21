<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\AssetSurface;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteAssetSurfaces
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): AssetSurface
    {
        return AssetSurface::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): AssetSurface
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): AssetSurface
    {
        return AssetSurface::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
