<?php

declare(strict_types=1);

namespace App\Features\Sales\Persistence;

use App\Features\Sales\Models\QuoteLine;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteQuoteLines
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): QuoteLine
    {
        return QuoteLine::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): QuoteLine
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): QuoteLine
    {
        return QuoteLine::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
