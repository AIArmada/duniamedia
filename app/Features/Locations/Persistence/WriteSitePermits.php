<?php

declare(strict_types=1);

namespace App\Features\Locations\Persistence;

use App\Features\Locations\Models\SitePermit;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteSitePermits
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): SitePermit
    {
        return SitePermit::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): SitePermit
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): SitePermit
    {
        return SitePermit::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
