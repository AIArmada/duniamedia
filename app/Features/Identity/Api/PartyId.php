<?php

declare(strict_types=1);

namespace App\Features\Identity\Api;

final readonly class PartyId
{
    public function __construct(public string $value) {}

    public function toString(): string
    {
        return $this->value;
    }
}
