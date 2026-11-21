<?php

declare(strict_types=1);

namespace App\Features\Sales\Actions;

use App\Features\Catalog\Api\RegistersMediaAssets;
use App\Features\Identity\Api\ManagesParties;
use App\Features\Locations\Api\RegistersSites;
use App\Features\Sales\Api\RecordsEnquiries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final readonly class RegisterAssetSubmission
{
    public function __construct(
        private ManagesParties $parties,
        private RegistersSites $sites,
        private RegistersMediaAssets $assets,
        private RecordsEnquiries $enquiries,
    ) {}

    /**
     * @param  array{name: string, email: string, phone?: string|null, company?: string|null, asset_type: string, location: string, description: string, photo_path?: string|null, photo_name?: string|null}  $submission
     * @return array{party_id: string, property_id: string, enquiry_id: string}
     */
    public function handle(array $submission): array
    {
        return DB::transaction(function () use ($submission): array {
            $party = $this->parties->createWithContact([
                'name' => $submission['name'],
                'email' => $submission['email'],
                'phone' => $submission['phone'] ?? null,
                'company' => $submission['company'] ?? null,
            ]);

            $slug = Str::slug(($submission['company'] ?? '') ?: $submission['asset_type']).'-'.Str::lower(Str::random(6));

            $site = $this->sites->registerProspect(
                ['name' => $submission['location']],
                ['structure_type' => $submission['asset_type'], 'label' => $submission['asset_type'], 'owner_party_id' => $party->value],
            );

            $propertyId = $this->assets->registerProspect(
                [
                    'name' => ($submission['company'] ?? '') ?: $submission['asset_type'],
                    'slug' => $slug,
                    'owner_party_id' => $party->value,
                    'description' => $submission['description'],
                ],
                [
                    'asset_type' => $submission['asset_type'],
                    'asset_code' => $slug,
                    'location' => $submission['location'],
                    'location_id' => $site['location_id'],
                    'host_structure_id' => $site['host_structure_id'],
                ],
            );

            $enquiryId = $this->enquiries->record($party->value, [
                'source' => 'asset_submission',
                'subject' => 'Media space submission',
                'brief' => $submission['description'],
                'consent_at' => now()->toIso8601String(),
                'metadata' => [
                    'asset_type' => $submission['asset_type'],
                    'location' => $submission['location'],
                    'property_id' => $propertyId,
                ],
            ]);

            if (($submission['photo_path'] ?? null) !== null && ($submission['photo_name'] ?? null) !== null) {
                $this->assets->attachLogo($propertyId, $submission['photo_path'], $submission['photo_name']);
            }

            return ['party_id' => $party->value, 'property_id' => $propertyId, 'enquiry_id' => $enquiryId];
        });
    }
}
