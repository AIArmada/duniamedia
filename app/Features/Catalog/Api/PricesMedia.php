<?php

declare(strict_types=1);

namespace App\Features\Catalog\Api;

interface PricesMedia
{
    public function unitPriceMinor(string $productId, int $quantity = 1): int;

    public function isPubliclyPriced(string $productId): bool;

    public function isCheckoutable(string $productId): bool;

    public function unitCode(string $productId): ?string;
}
