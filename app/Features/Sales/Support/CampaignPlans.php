<?php

declare(strict_types=1);

namespace App\Features\Sales\Support;

use AIArmada\Cart\Collections\CartCollection;
use AIArmada\Cart\Collections\CartConditionCollection;
use AIArmada\Cart\Facades\Cart;
use AIArmada\Cart\Models\CartModel;
use AIArmada\Cart\Support\CartIdentifierResolver;
use AIArmada\Products\Models\Product;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Catalog\Api\PresentsRateLines;
use App\Features\Catalog\Api\RateLineView;
use App\Features\Sales\Persistence\WriteCampaignPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CampaignPlans
{
    public function identifier(): string
    {
        return app(CartIdentifierResolver::class)->resolve();
    }

    /**
     * @return Collection<int, CartModel>
     */
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

    public function draft(): CartModel
    {
        $instance = (string) session('campaign-plan-draft', '');

        if ($instance !== '' && ($plan = $this->find($instance)) !== null) {
            return $plan;
        }

        $plan = $this->create('Campaign plan '.now()->format('d M Y'));
        session(['campaign-plan-draft' => $plan->instance]);

        return $plan;
    }

    public function addRateLine(CartModel $plan, RateLineView $line, int $quantity = 1): void
    {
        if (! $line->isPriced()) {
            throw new InvalidArgumentException('Only priced rate lines can be added to a plan.');
        }

        Cart::getCartInstance($plan->instance)->addBuyable(new RateLineBuyable($line), $quantity);
    }

    public function addOffering(CartModel $plan, Product $product, int $quantity = 1): void
    {
        $offer = app(PresentsMedia::class)->view($product->id);

        Cart::getCartInstance($plan->instance)->addBuyable($product, $quantity, [
            'image' => $offer?->imageUrl,
            'medium' => $offer?->medium,
            'location' => $offer?->locationLabel,
        ]);
    }

    public function updateQuantity(CartModel $plan, string $itemId, int $quantity): void
    {
        $cart = Cart::getCartInstance($plan->instance);
        $item = $cart->getItems()->getItem($itemId);

        abort_unless($item, 404);

        $rateEntryId = $item->attributes->get('rate_entry_id');

        if (is_string($rateEntryId) && $rateEntryId !== '') {
            $line = app(PresentsRateLines::class)->find($rateEntryId);
            abort_unless($line && $line->isPriced(), 404);
            $buyable = new RateLineBuyable($line);
        } else {
            $buyable = Product::query()->findOrFail($item->attributes->get('product_id'));
            abort_unless(app(PresentsMedia::class)->contains($buyable->id), 404);
        }

        $cart->updateBuyable($buyable, max(1, $quantity), $itemId);
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
        app(WriteCampaignPlan::class)->markConverted($plan);
    }

    public function delete(CartModel $plan): void
    {
        app(WriteCampaignPlan::class)->delete($plan);
    }

    /**
     * @return Builder<CartModel>
     */
    private function query(): Builder
    {
        return CartModel::query()
            ->where('identifier', $this->identifier())
            ->whereNull('checked_out_at');
    }
}
