<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\RateCardSection;
use Illuminate\Support\Facades\DB;

final readonly class WriteRateCardSections
{
    /**
     * @param  list<array<string, mixed>>  $rows
     * @return array<string, RateCardSection>
     */
    public function replaceForProduct(Product $product, array $rows): array
    {
        return DB::transaction(function () use ($product, $rows): array {
            $existing = RateCardSection::query()
                ->where('product_id', $product->id)
                ->pluck('id')
                ->all();

            if ($existing !== []) {
                DB::table('rate_card_section_media_property')->whereIn('section_id', $existing)->delete();
            }

            RateCardSection::query()->where('product_id', $product->id)->delete();

            $sections = [];

            foreach ($rows as $index => $row) {
                $channels = $row['channel_ids'] ?? [];
                unset($row['channel_ids']);

                $section = RateCardSection::query()->create([
                    'product_id' => $product->id,
                    'key' => $row['key'],
                    'title' => $row['title'],
                    'intro' => $row['intro'] ?? null,
                    'medium' => $row['medium'] ?? null,
                    'sort_order' => $row['sort_order'] ?? $index,
                ]);

                if ($channels !== []) {
                    $now = now();

                    DB::table('rate_card_section_media_property')->insert(
                        array_map(fn (string $channelId): array => [
                            'section_id' => $section->id,
                            'media_property_id' => $channelId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ], $channels)
                    );
                }

                $sections[$section->key] = $section;
            }

            return $sections;
        });
    }
}
