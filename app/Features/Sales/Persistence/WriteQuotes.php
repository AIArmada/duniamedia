<?php

declare(strict_types=1);

namespace App\Features\Sales\Persistence;

use App\Features\Sales\Models\Quote;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteQuotes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Quote
    {
        return Quote::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): Quote
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): Quote
    {
        return Quote::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
