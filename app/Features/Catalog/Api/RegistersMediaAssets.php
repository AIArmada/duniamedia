<?php

declare(strict_types=1);

namespace App\Features\Catalog\Api;

interface RegistersMediaAssets
{
    /**
     * @param  array{name: string, slug: string, description?: string|null, owner_party_id: string, property_type?: string}  $property
     * @param  array{asset_type: string, asset_code: string, location?: string|null, location_id?: string|null, host_structure_id?: string|null}  $asset
     */
    public function registerProspect(array $property, array $asset): string;

    public function attachLogo(string $mediaPropertyId, string $sourcePath, string $fileName): void;

    /**
     * @param  array{name: string, slug: string, owner_party_id: string, property_type: string, status?: string}  $property
     * @param  array{distribution_platform_id: string, handle: string, is_primary?: bool}  $link
     */
    public function registerPropertyWithPlatform(array $property, array $link): string;

    public function distributionPlatformId(string $name, string $kind): string;
}
