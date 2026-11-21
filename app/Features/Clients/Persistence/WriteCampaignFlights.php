<?php

declare(strict_types=1);

namespace App\Features\Clients\Persistence;

use App\Features\Clients\Models\CampaignFlight;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteCampaignFlights
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): CampaignFlight
    {
        return CampaignFlight::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): CampaignFlight
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): CampaignFlight
    {
        return CampaignFlight::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
