<?php

declare(strict_types=1);

namespace App\Shared\Contracts;

final readonly class StrategySynthesisRequest
{
    /**
     * @param  list<string>  $regions
     * @param  list<string>  $languages
     * @param  list<string>  $preferredMediums
     * @param  list<array{product_id: string, name: string, medium: string|null, location: string|null, channel: string|null, audience: string|null, price_from: float|null, unit_code: string|null}>  $candidates
     */
    public function __construct(
        public string $objective,
        public float $budgetAmount,
        public string $currencyCode,
        public array $regions,
        public array $languages,
        public ?string $industry,
        public ?string $audience,
        public string $timeline,
        public ?string $launchWindow,
        public array $preferredMediums,
        public string $creativeReadiness,
        public ?string $brandContext,
        public array $candidates,
    ) {}
}
