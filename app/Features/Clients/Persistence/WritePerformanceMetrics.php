<?php

declare(strict_types=1);

namespace App\Features\Clients\Persistence;

use App\Features\Clients\Models\PerformanceMetric;
use Illuminate\Database\Eloquent\Model;

final readonly class WritePerformanceMetrics
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): PerformanceMetric
    {
        return PerformanceMetric::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): PerformanceMetric
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): PerformanceMetric
    {
        return PerformanceMetric::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }
}
