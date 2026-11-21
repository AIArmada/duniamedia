<?php

declare(strict_types=1);

namespace App\Features\Clients\Persistence;

use App\Features\Clients\Models\Campaign;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteCampaigns
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Campaign
    {
        return Campaign::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): Campaign
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): Campaign
    {
        return Campaign::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }

    public function findByCode(string $code): ?Campaign
    {
        return Campaign::query()->where('campaign_code', $code)->first();
    }
}
