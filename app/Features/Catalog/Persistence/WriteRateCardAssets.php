<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\RateCardAsset;
use Illuminate\Support\Facades\DB;

final readonly class WriteRateCardAssets
{
    /**
     * @param  list<array<string, mixed>>  $rows
     */
    public function replaceForProduct(Product $product, array $rows): int
    {
        return DB::transaction(function () use ($product, $rows): int {
            RateCardAsset::query()->where('product_id', $product->id)->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                RateCardAsset::query()->insert($chunk);
            }

            return count($rows);
        });
    }
}
