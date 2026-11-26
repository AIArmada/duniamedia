<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\MediaProperty;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteMediaProperties
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): MediaProperty
    {
        return MediaProperty::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): MediaProperty
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): MediaProperty
    {
        return MediaProperty::query()->updateOrCreate($keys, $attributes);
    }

    /**
     * @param  list<string>  $slugs
     */
    public function publishOnly(array $slugs): void
    {
        MediaProperty::query()->update(['is_public' => false]);

        if ($slugs !== []) {
            MediaProperty::query()->whereIn('slug', $slugs)->update(['is_public' => true]);
        }
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }

    public function findBySlug(string $slug): ?MediaProperty
    {
        return MediaProperty::query()->where('slug', $slug)->first();
    }

    /**
     * @return Collection<int, MediaProperty>
     */
    public function byOwner(string $ownerPartyId)
    {
        return MediaProperty::query()->where('owner_party_id', $ownerPartyId)->get();
    }
}
