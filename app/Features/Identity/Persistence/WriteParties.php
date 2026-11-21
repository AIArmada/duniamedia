<?php

declare(strict_types=1);

namespace App\Features\Identity\Persistence;

use App\Features\Identity\Models\Organization;
use App\Features\Identity\Models\Party;
use App\Features\Identity\Models\PartyRelationship;
use App\Features\Identity\Models\PartyRole;

final readonly class WriteParties
{
    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function party(array $keys, array $attributes): Party
    {
        return Party::query()->updateOrCreate($keys, $attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateParty(Party $party, array $attributes): Party
    {
        $party->fill($attributes);
        $party->save();

        return $party;
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function organization(array $keys, array $attributes): Organization
    {
        return Organization::query()->updateOrCreate($keys, $attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function role(array $keys, array $attributes): PartyRole
    {
        return PartyRole::query()->updateOrCreate($keys, $attributes);
    }

    /**
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $attributes
     */
    public function relationship(array $keys, array $attributes): PartyRelationship
    {
        return PartyRelationship::query()->updateOrCreate($keys, $attributes);
    }

    public function findBySlug(string $slug): ?Party
    {
        return Party::query()->where('slug', $slug)->first();
    }
}
