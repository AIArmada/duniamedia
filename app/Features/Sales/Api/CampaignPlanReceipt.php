<?php

declare(strict_types=1);

namespace App\Features\Sales\Api;

final readonly class CampaignPlanReceipt
{
    public function __construct(
        public string $instance,
        public string $name,
        public int $itemCount,
    ) {}
}
