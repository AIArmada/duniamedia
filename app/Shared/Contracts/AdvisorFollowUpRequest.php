<?php

declare(strict_types=1);

namespace App\Shared\Contracts;

final readonly class AdvisorFollowUpRequest
{
    /**
     * @param  array<string, mixed>  $brief
     * @param  list<array{role: string, content: string}>  $history
     * @param  list<array{product_id: string, name: string, medium: string|null, location: string|null, price_from: float|null}>  $candidates
     * @param  list<array{key: string, name: string, estimated_subtotal: float|null}>  $scenarios
     */
    public function __construct(
        public string $question,
        public array $brief,
        public array $history,
        public array $candidates,
        public array $scenarios,
    ) {}
}
