<?php

declare(strict_types=1);

namespace App\Features\Clients\Persistence;

use App\Features\Clients\Models\ProjectParty;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteProjectParties
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): ProjectParty
    {
        return ProjectParty::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): ProjectParty
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): ProjectParty
    {
        return ProjectParty::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
