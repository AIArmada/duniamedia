<?php

declare(strict_types=1);

namespace App\Features\Clients\Persistence;

use App\Features\Clients\Models\Brand;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteBrands
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Brand
    {
        return Brand::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): Brand
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): Brand
    {
        return Brand::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
