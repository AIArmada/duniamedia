<?php

declare(strict_types=1);

namespace App\Features\Identity\Api;

final readonly class PartySummary
{
    public function __construct(
        public string $id,
        public string $displayName,
        public string $slug,
        public string $partyType,
        public ?string $sector = null,
        public ?string $industry = null,
        public ?string $logoUrl = null,
        public ?string $notes = null,
    ) {}
}
