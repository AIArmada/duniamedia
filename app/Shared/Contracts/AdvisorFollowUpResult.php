<?php

declare(strict_types=1);

namespace App\Shared\Contracts;

final readonly class AdvisorFollowUpResult
{
    /**
     * @param  list<string>  $suggestions
     */
    public function __construct(
        public string $reply,
        public array $suggestions,
    ) {}
}
