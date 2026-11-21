<?php

declare(strict_types=1);

namespace App\Features\Catalog\Support;

use AIArmada\Pricing\Contracts\PriceCalculatorInterface;
use AIArmada\Products\Models\Product;

class CataloguePricing
{
    /**
     * Price in minor units from the active price list.
     */
    public function unitPrice(Product $product, int $quantity = 1): int
    {
        if ($product->mediaProfile?->pricing_mode !== 'fixed') {
            return 0;
        }

        return app(PriceCalculatorInterface::class)->calculate($product, $quantity)->finalPrice;
    }

    public function isPubliclyPriced(Product $product): bool
    {
        return $product->mediaProfile?->pricing_mode === 'fixed'
            && $this->unitPrice($product) > 0;
    }

    public function isCheckoutable(Product $product): bool
    {
        return $product->status?->value === 'active'
            && $this->isPubliclyPriced($product);
    }
}
