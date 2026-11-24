<?php

declare(strict_types=1);

namespace App\Features\Sales\Api;

interface CreatesCampaignPlans
{
    /**
     * Create a saved campaign plan from a mix of active catalogue offerings.
     *
     * @param  list<array{product_id: string, quantity?: int}>  $items
     * @param  array<string, mixed>  $metadata
     * @param  array{name: string, value: string}|null  $discount
     */
    public function createFromOfferings(string $name, array $items, array $metadata = [], ?array $discount = null): CampaignPlanReceipt;
}
