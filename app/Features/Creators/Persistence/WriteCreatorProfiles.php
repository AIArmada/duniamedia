<?php

declare(strict_types=1);

namespace App\Features\Creators\Persistence;

use App\Features\Creators\Models\CreatorProfile;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteCreatorProfiles
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): CreatorProfile
    {
        return CreatorProfile::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): CreatorProfile
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): CreatorProfile
    {
        return CreatorProfile::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }

    public function findBySlug(string $slug): ?CreatorProfile
    {
        return CreatorProfile::query()->where('slug', $slug)->first();
    }
}
