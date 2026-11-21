<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\MediaType;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteMediaTypes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): MediaType
    {
        return MediaType::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): MediaType
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): MediaType
    {
        return MediaType::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }

    public function findBySlug(string $slug): ?MediaType
    {
        return MediaType::query()->where('slug', $slug)->first();
    }
}
