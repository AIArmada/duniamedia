<?php

declare(strict_types=1);

namespace App\Shared\Contracts;

final readonly class StrategySynthesisResult
{
    /**
     * @param  array<string, array{name: string, headline: string, messaging: string, creative_angle: string, diagnosis: string, product_ids: list<string>}>  $scenarios
     * @param  list<string>  $questions
     */
    public function __construct(
        public string $headline,
        public string $diagnosis,
        public array $scenarios,
        public array $questions,
    ) {}
}
