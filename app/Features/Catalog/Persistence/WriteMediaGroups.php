<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\MediaGroup;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteMediaGroups
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): MediaGroup
    {
        return MediaGroup::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): MediaGroup
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): MediaGroup
    {
        return MediaGroup::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }

    public function findBySlug(string $slug): ?MediaGroup
    {
        return MediaGroup::query()->where('slug', $slug)->first();
    }
}
