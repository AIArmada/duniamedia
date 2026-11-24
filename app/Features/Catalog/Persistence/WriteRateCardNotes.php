<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\RateCardNote;
use Illuminate\Support\Facades\DB;

final readonly class WriteRateCardNotes
{
    /**
     * @param  list<array<string, mixed>>  $rows
     */
    public function replaceAll(array $rows): int
    {
        return DB::transaction(function () use ($rows): int {
            RateCardNote::query()->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                RateCardNote::query()->insert($chunk);
            }

            return count($rows);
        });
    }
}
