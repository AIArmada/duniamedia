<?php

declare(strict_types=1);

namespace App\Features\Creators\Persistence;

use App\Features\Creators\Models\CreatorAccount;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteCreatorAccounts
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): CreatorAccount
    {
        return CreatorAccount::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): CreatorAccount
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): CreatorAccount
    {
        return CreatorAccount::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
