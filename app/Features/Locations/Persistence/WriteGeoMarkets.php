<?php

declare(strict_types=1);

namespace App\Features\Locations\Persistence;

use App\Features\Locations\Models\GeoMarket;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteGeoMarkets
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): GeoMarket
    {
        return GeoMarket::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): GeoMarket
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): GeoMarket
    {
        return GeoMarket::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
