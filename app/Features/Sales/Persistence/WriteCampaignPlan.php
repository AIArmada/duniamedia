<?php

declare(strict_types=1);

namespace App\Features\Sales\Persistence;

use AIArmada\Cart\Facades\Cart;
use AIArmada\Cart\Models\CartModel;
use AIArmada\Products\Models\Product;
use Illuminate\Support\Str;

final readonly class WriteCampaignPlan
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function create(string $instance, string $name, array $metadata = []): CartModel
    {
        Cart::getCartInstance($instance)->setMetadataBatch(array_merge([
            'name' => $name,
            'status' => 'draft',
        ], $metadata));

        return CartModel::query()->where('instance', $instance)->firstOrFail();
    }

    public function addOffering(CartModel $plan, Product $product, int $quantity = 1): void
    {
        Cart::getCartInstance($plan->instance)->addBuyable($product, $quantity);
    }

    public function updateQuantity(CartModel $plan, string $itemId, int $quantity): void
    {
        Cart::getCartInstance($plan->instance)->update($itemId, [
            'quantity' => ['value' => max(1, $quantity)],
        ]);
    }

    public function removeItem(CartModel $plan, string $itemId): void
    {
        Cart::getCartInstance($plan->instance)->remove($itemId);
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    public function setDetails(CartModel $plan, array $metadata): void
    {
        Cart::getCartInstance($plan->instance)->setMetadataBatch($metadata);
    }

    public function applyDiscount(CartModel $plan, string $name, string $value): void
    {
        Cart::getCartInstance($plan->instance)->addDiscount($name, $value);
    }

    public function removeDiscount(CartModel $plan, string $name): void
    {
        Cart::getCartInstance($plan->instance)->removeCondition($name);
    }

    public function clearDiscounts(CartModel $plan): void
    {
        Cart::getCartInstance($plan->instance)->clearConditions();
    }

    public function markConverted(CartModel $plan): void
    {
        $plan->markAsConverted();
    }

    public function delete(CartModel $plan): void
    {
        $plan->delete();
    }

    public function newInstance(): string
    {
        return 'plan-'.Str::uuid();
    }
}
