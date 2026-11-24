<?php

declare(strict_types=1);

namespace App\Features\Catalog\Api;

interface PresentsRateLines
{
    public function find(string $rateLineId): ?RateLineView;
}
