<?php

declare(strict_types=1);

namespace App\Features\Clients\Persistence;

use App\Features\Clients\Models\ClientReference;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteClientReferences
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): ClientReference
    {
        return ClientReference::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): ClientReference
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): ClientReference
    {
        return ClientReference::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
