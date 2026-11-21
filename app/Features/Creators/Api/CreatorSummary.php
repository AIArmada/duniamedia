<?php

declare(strict_types=1);

namespace App\Features\Creators\Api;

final readonly class CreatorSummary
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public ?string $niche,
        public ?string $socialPlatform,
        public ?string $handle,
        public ?string $bio,
        public bool $isFeatured,
        public string $imageUrl,
    ) {}
}
