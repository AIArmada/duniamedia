<?php

declare(strict_types=1);

namespace App\Features\Identity\Api;

interface AuthorizesActors
{
    /**
     * Throws when the actor may not manage sales.
     */
    public function assertCanManageSales(string $actorPartyId): void;
}
