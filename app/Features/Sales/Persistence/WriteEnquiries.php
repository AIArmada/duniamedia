<?php

declare(strict_types=1);

namespace App\Features\Sales\Persistence;

use App\Features\Sales\Models\Enquiry;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteEnquiries
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Enquiry
    {
        return Enquiry::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): Enquiry
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): Enquiry
    {
        return Enquiry::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
