<?php

namespace App\Support;

use AIArmada\Cart\Collections\CartCollection;
use AIArmada\Cart\Collections\CartConditionCollection;
use AIArmada\Cart\Facades\Cart;
use AIArmada\Cart\Models\CartModel;
use AIArmada\Cart\Support\CartIdentifierResolver;
use App\Models\MediaOffering;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CampaignPlans
{
    public function identifier(): string
    {
        return app(CartIdentifierResolver::class)->resolve();
    }

    public function all(): Collection
    {
        return $this->query()->orderByDesc('updated_at')->get();
    }

    public function find(string $instance): ?CartModel
    {
        return $this->query()->where('instance', $instance)->first();
    }

    public function create(string $name, array $metadata = []): CartModel
    {
        $instance = 'plan-'.Str::uuid();

        Cart::getCartInstance($instance)->setMetadataBatch(array_merge([
            'name' => $name,
            'status' => 'draft',
        ], $metadata));

        return $this->find($instance)
            ?? throw new \RuntimeException('The campaign plan could not be created.');
    }

    public function addOffering(CartModel $plan, MediaOffering $offering, int $quantity = 1): void
    {
        Cart::getCartInstance($plan->instance)->addBuyable($offering, $quantity);
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

    /**
     * @return array{subtotal: int, total: int, discount: int, items: CartCollection, conditions: CartConditionCollection}
     */
    public function summary(CartModel $plan): array
    {
        $cart = Cart::getCartInstance($plan->instance);

        return [
            'subtotal' => $cart->getRawSubtotalWithoutConditions(),
            'total' => $cart->getRawTotal(),
            'discount' => (int) $cart->savings()->getAmount(),
            'items' => $cart->getItems(),
            'conditions' => $cart->getConditions(),
        ];
    }

    public function markConverted(CartModel $plan): void
    {
        $plan->markAsConverted();
    }

    public function delete(CartModel $plan): void
    {
        $plan->delete();
    }

    private function query(): Builder
    {
        return CartModel::query()
            ->where('identifier', $this->identifier())
            ->whereNull('checked_out_at');
    }
}
