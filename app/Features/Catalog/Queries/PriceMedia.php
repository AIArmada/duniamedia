<?php

declare(strict_types=1);

namespace App\Features\Catalog\Queries;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Api\PricesMedia;
use App\Features\Catalog\Support\CataloguePricing;

final readonly class PriceMedia implements PricesMedia
{
    public function __construct(private CataloguePricing $pricing) {}

    public function unitPriceMinor(string $productId, int $quantity = 1): int
    {
        $product = Product::query()->with('mediaProfile')->find($productId);

        return $product === null ? 0 : $this->pricing->unitPrice($product, $quantity);
    }

    public function isPubliclyPriced(string $productId): bool
    {
        $product = Product::query()->with('mediaProfile')->find($productId);

        return $product !== null && $this->pricing->isPubliclyPriced($product);
    }

    public function unitCode(string $productId): ?string
    {
        return Product::query()->with('mediaProfile')->find($productId)?->mediaProfile?->unit_code;
    }

    public function isCheckoutable(string $productId): bool
    {
        $product = Product::query()->with('mediaProfile')->find($productId);

        return $product !== null && $this->pricing->isCheckoutable($product);
    }
}
