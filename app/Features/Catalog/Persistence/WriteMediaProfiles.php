<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\MediaProfile;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteMediaProfiles
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): MediaProfile
    {
        return MediaProfile::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): MediaProfile
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): MediaProfile
    {
        return MediaProfile::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
