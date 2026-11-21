<?php

declare(strict_types=1);

namespace App\Features\Locations\Actions;

use App\Features\Locations\Api\RegistersSites;
use App\Features\Locations\Models\HostStructure;
use App\Features\Locations\Models\Location;
use Illuminate\Support\Facades\DB;

final readonly class RegisterSite implements RegistersSites
{
    /**
     * @param  array{name: string, status?: string}  $location
     * @param  array{structure_type: string, label: string, owner_party_id: string}  $structure
     * @return array{location_id: string, host_structure_id: string}
     */
    public function registerProspect(array $location, array $structure): array
    {
        return DB::transaction(function () use ($location, $structure): array {
            $site = Location::query()->create([
                'location_type' => 'site',
                'name' => $location['name'],
                'status' => $location['status'] ?? 'prospect',
                'is_public' => false,
            ]);

            $host = HostStructure::query()->create([
                'location_id' => $site->id,
                'owner_party_id' => $structure['owner_party_id'],
                'structure_type' => $structure['structure_type'],
                'label' => $structure['label'],
                'status' => 'prospect',
            ]);

            return ['location_id' => $site->id, 'host_structure_id' => $host->id];
        });
    }
}
