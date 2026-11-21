<?php

declare(strict_types=1);

namespace App\Features\Creators\Api;

interface ResolvesCreators
{
    public function summary(string $creatorProfileId): ?CreatorSummary;
}
