<?php

declare(strict_types=1);

namespace App\Features\Clients\Api;

use App\Features\Identity\Api\PartySummary;

interface ListsClientParties
{
    /**
     * Parties carrying a published client reference, ordered by display name.
     *
     * @return list<PartySummary>
     */
    public function clientParties(): array;
}
