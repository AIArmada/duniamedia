<?php

declare(strict_types=1);

namespace App\Features\Locations\Api;

interface RegistersSites
{
    /**
     * @param  array{name: string, status?: string}  $location
     * @param  array{structure_type: string, label: string, owner_party_id: string}  $structure
     * @return array{location_id: string, host_structure_id: string}
     */
    public function registerProspect(array $location, array $structure): array;
}
