<?php

declare(strict_types=1);

namespace App\Features\Content\Actions;

use App\Features\Content\Models\Training;
use Illuminate\Support\Arr;

final readonly class SaveTraining
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes, ?Training $training = null): Training
    {
        $training ??= new Training;
        $training->fill(Arr::except($attributes, ['id', 'created_at', 'updated_at']));
        $training->save();

        return $training;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): Training
    {
        return Training::query()->updateOrCreate($keys, $attributes);
    }
}
