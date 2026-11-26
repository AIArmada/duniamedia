<?php

declare(strict_types=1);

namespace App\Features\Catalog\Api;

final readonly class RateLineView
{
    public function __construct(
        public string $id,
        public string $productId,
        public string $productSlug,
        public string $productName,
        public string $item,
        public ?string $unit,
        public ?float $price,
        public ?string $channelName,
        public ?string $channelMedium,
        public ?string $sectionTitle,
        public string $image,
        public ?string $location,
        public bool $selectable = true,
    ) {}

    public function isPriced(): bool
    {
        return $this->selectable && $this->price !== null && $this->price > 0;
    }
}
