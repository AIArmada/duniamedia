<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\RateCardEntry;
use Illuminate\Support\Facades\DB;

final readonly class WriteRateCardEntries
{
    /**
     * @param  list<array<string, mixed>>  $rows
     */
    public function replaceAll(array $rows): int
    {
        return DB::transaction(function () use ($rows): int {
            RateCardEntry::query()->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                RateCardEntry::query()->insert($chunk);
            }

            return count($rows);
        });
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     */
    public function replaceForProduct(Product|string $product, array $rows): int
    {
        $productId = $product instanceof Product ? $product->id : $product;

        return DB::transaction(function () use ($productId, $rows): int {
            RateCardEntry::query()->where('product_id', $productId)->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                RateCardEntry::query()->insert($chunk);
            }

            return count($rows);
        });
    }
}
