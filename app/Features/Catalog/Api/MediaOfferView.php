<?php

declare(strict_types=1);

namespace App\Features\Catalog\Api;

final readonly class MediaOfferView
{
    /**
     * @param  array<string, mixed>  $specifications
     */
    public function __construct(
        public string $id,
        public string $slug,
        public string $name,
        public ?string $summary,
        public ?string $description,
        public bool $isFeatured,
        public ?string $status,
        public ?string $medium,
        public ?string $channelLabel,
        public ?string $locationLabel,
        public ?string $audienceLabel,
        public ?string $pricingMode,
        public ?float $priceFrom,
        public ?string $availabilityStatus,
        public ?string $verifiedAt,
        public ?string $sellerName,
        public ?string $sellerId,
        public string $imageUrl,
        public array $specifications,
        public ?string $unitCode = null,
    ) {}

    public function displayImage(): string
    {
        return $this->imageUrl;
    }
}
