<?php

declare(strict_types=1);

namespace App\Features\Clients\Persistence;

use App\Features\Clients\Models\Project;
use Illuminate\Database\Eloquent\Model;

final readonly class WriteProjects
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Project
    {
        return Project::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Model $record, array $attributes): Project
    {
        $record->fill($attributes);
        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function upsert(array $keys, array $attributes): Project
    {
        return Project::query()->updateOrCreate($keys, $attributes);
    }

    public function delete(Model $record): void
    {
        $record->delete();
    }

    public function findByCode(string $code): ?Project
    {
        return Project::query()->where('project_code', $code)->first();
    }
}
