<?php

declare(strict_types=1);

namespace App\Features\Creators\Persistence;

use App\Features\Creators\Models\CreatorDeliveryTemplate;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteCreatorDeliveryTemplates
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): CreatorDeliveryTemplate
    {
        return CreatorDeliveryTemplate::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): CreatorDeliveryTemplate
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): CreatorDeliveryTemplate
    {
        return CreatorDeliveryTemplate::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
