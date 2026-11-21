<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\DistributionPlatform;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteDistributionPlatforms
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): DistributionPlatform
    {
        return DistributionPlatform::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): DistributionPlatform
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): DistributionPlatform
    {
        return DistributionPlatform::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }

    public function findByKind(string $kind): ?DistributionPlatform
    {
        return DistributionPlatform::query()->where('platform_kind', $kind)->first();
    }

    public function findByName(string $name): ?DistributionPlatform
    {
        return DistributionPlatform::query()->where('name', $name)->first();
    }
}
