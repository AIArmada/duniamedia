<?php

declare(strict_types=1);

namespace App\Features\Identity\Persistence;

use App\Features\Identity\Models\PartyRole;

final readonly class AssignPartyRole
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(string $partyId, string $roleCode, array $attributes = []): PartyRole
    {
        return PartyRole::query()->updateOrCreate(
            ['party_id' => $partyId, 'role_code' => $roleCode],
            $attributes,
        );
    }
}
