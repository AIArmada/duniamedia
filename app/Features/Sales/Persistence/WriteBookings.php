<?php

declare(strict_types=1);

namespace App\Features\Sales\Persistence;

use App\Features\Sales\Models\Booking;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteBookings
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Booking
    {
        return Booking::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): Booking
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): Booking
    {
        return Booking::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
