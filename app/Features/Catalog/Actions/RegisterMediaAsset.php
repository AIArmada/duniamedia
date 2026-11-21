<?php

declare(strict_types=1);

namespace App\Features\Catalog\Actions;

use App\Features\Catalog\Api\RegistersMediaAssets;
use App\Features\Catalog\Models\DistributionPlatform;
use App\Features\Catalog\Models\MediaAsset;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Models\PropertyDistributionPlatform;
use Illuminate\Support\Facades\DB;

final readonly class RegisterMediaAsset implements RegistersMediaAssets
{
    /**
     * @param  array{name: string, slug: string, description?: string|null, owner_party_id: string, property_type?: string}  $property
     * @param  array{asset_type: string, asset_code: string, location?: string|null, location_id?: string|null, host_structure_id?: string|null}  $asset
     */
    public function registerProspect(array $property, array $asset): string
    {
        return DB::transaction(function () use ($property, $asset): string {
            $record = $this->createProperty($property);

            MediaAsset::query()->create([
                'media_property_id' => $record->id,
                'location_id' => $asset['location_id'] ?? null,
                'host_structure_id' => $asset['host_structure_id'] ?? null,
                'asset_type' => $asset['asset_type'],
                'asset_code' => $asset['asset_code'],
                'name' => $asset['asset_type'],
                'status' => 'prospect',
                'specs' => [
                    'asset_type' => $asset['asset_type'],
                    'location' => $asset['location'] ?? null,
                ],
            ]);

            return $record->id;
        });
    }

    public function attachLogo(string $mediaPropertyId, string $sourcePath, string $fileName): void
    {
        MediaProperty::query()
            ->findOrFail($mediaPropertyId)
            ->addMedia($sourcePath)
            ->preservingOriginal()
            ->usingFileName($fileName)
            ->toMediaCollection('logo');
    }

    /**
     * @param  array{name: string, slug: string, owner_party_id: string, property_type: string, status?: string}  $property
     * @param  array{distribution_platform_id: string, handle: string, is_primary?: bool}  $link
     */
    public function registerPropertyWithPlatform(array $property, array $link): string
    {
        return DB::transaction(function () use ($property, $link): string {
            $record = $this->createProperty($property);

            PropertyDistributionPlatform::query()->create([
                'media_property_id' => $record->id,
                'distribution_platform_id' => $link['distribution_platform_id'],
                'handle' => $link['handle'],
                'is_primary' => $link['is_primary'] ?? true,
            ]);

            return $record->id;
        });
    }

    public function distributionPlatformId(string $name, string $kind): string
    {
        return DistributionPlatform::query()->firstOrCreate(
            ['name' => $name],
            ['platform_kind' => $kind],
        )->id;
    }

    /**
     * @param  array{name: string, slug: string, description?: string|null, owner_party_id: string, property_type?: string, status?: string}  $property
     */
    private function createProperty(array $property): MediaProperty
    {
        return MediaProperty::query()->create([
            'owner_party_id' => $property['owner_party_id'],
            'property_type' => $property['property_type'] ?? 'prospect',
            'name' => $property['name'],
            'slug' => $property['slug'],
            'status' => $property['status'] ?? 'prospect',
            'description' => $property['description'] ?? null,
        ]);
    }
}
