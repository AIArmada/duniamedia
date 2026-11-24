<?php

declare(strict_types=1);

namespace App\Features\Clients\Api;

final readonly class ServiceSummary
{
    public function __construct(
        public string $id,
        public string $slug,
        public string $name,
        public ?string $category,
        public ?string $summary,
    ) {}
}
