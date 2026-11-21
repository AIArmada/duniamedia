<?php

declare(strict_types=1);

namespace App\Features\Identity\Queries;

use App\Features\Identity\Api\AuthorizesActors;
use App\Features\Identity\Models\PartyRole;
use App\Features\Identity\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

final readonly class AuthorizeActors implements AuthorizesActors
{
    private const MANAGER_ROLES = ['seller', 'admin', 'staff'];

    public function assertCanManageSales(string $actorPartyId): void
    {
        $holdsRole = PartyRole::query()
            ->where('party_id', $actorPartyId)
            ->whereIn('role_code', self::MANAGER_ROLES)
            ->exists();

        if ($holdsRole) {
            return;
        }

        $holdsPermission = User::query()
            ->where('party_id', $actorPartyId)
            ->get()
            ->contains(fn (User $user): bool => $user->can('quotes.manage'));

        if ($holdsPermission) {
            return;
        }

        throw new AuthorizationException('This actor is not permitted to manage sales.');
    }
}
