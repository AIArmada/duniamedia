<?php

declare(strict_types=1);

namespace App\Features\Sales\Actions;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Sales\Api\CampaignPlanReceipt;
use App\Features\Sales\Api\CreatesCampaignPlans;
use App\Features\Sales\Support\CampaignPlans;
use InvalidArgumentException;

final readonly class CreateCampaignPlanFromMix implements CreatesCampaignPlans
{
    public function __construct(
        private PresentsMedia $media,
        private CampaignPlans $plans,
    ) {}

    /**
     * @param  list<array{product_id: string, quantity?: int}>  $items
     * @param  array<string, mixed>  $metadata
     * @param  array{name: string, value: string}|null  $discount
     */
    public function createFromOfferings(string $name, array $items, array $metadata = [], ?array $discount = null): CampaignPlanReceipt
    {
        $selection = [];

        foreach ($items as $item) {
            $productId = $item['product_id'];
            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            if ($productId === '' || ! $this->media->contains($productId)) {
                continue;
            }

            $selection[$productId] = $quantity;
        }

        if ($selection === []) {
            throw new InvalidArgumentException('A campaign plan requires at least one active catalogue offering.');
        }

        $plan = $this->plans->create($name, $metadata);

        foreach ($selection as $productId => $quantity) {
            $product = Product::query()->find($productId);

            if ($product === null) {
                continue;
            }

            $this->plans->addOffering($plan, $product, $quantity);
        }

        if ($discount !== null && $discount['name'] !== '') {
            $this->plans->applyDiscount($plan, $discount['name'], $discount['value']);
        }

        return new CampaignPlanReceipt(
            instance: $plan->instance,
            name: (string) $plan->getMetadataValue('name', $name),
            itemCount: count($selection),
        );
    }
}
