<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Models\RateCardBlock;
use Illuminate\Support\Facades\DB;

final readonly class WriteRateCardBlocks
{
    /**
     * @param  list<array<string, mixed>>  $rows
     */
    public function replaceFor(Product|MediaProperty $model, array $rows): int
    {
        return DB::transaction(function () use ($model, $rows): int {
            RateCardBlock::query()
                ->where('blockable_type', $model::class)
                ->where('blockable_id', $model->getKey())
                ->delete();

            foreach ($rows as $index => $row) {
                RateCardBlock::query()->create([
                    'blockable_type' => $model::class,
                    'blockable_id' => $model->getKey(),
                    'type' => $row['type'],
                    'payload' => $row['payload'] ?? [],
                    'sort_order' => $row['sort_order'] ?? $index,
                ]);
            }

            return count($rows);
        });
    }
}
