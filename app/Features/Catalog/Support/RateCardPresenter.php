<?php

declare(strict_types=1);

namespace App\Features\Catalog\Support;

use App\Features\Catalog\Models\MediaProfile;
use App\Features\Catalog\Models\RateCardSection;
use Illuminate\Database\Eloquent\Collection;

final class RateCardPresenter
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function forProduct(string $productId): array
    {
        $sections = RateCardSection::query()
            ->where('product_id', $productId)
            ->with(['channels', 'entries', 'product.mediaProfile'])
            ->orderBy('sort_order')
            ->get();

        return self::present($sections);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function forChannel(string $mediaPropertyId): array
    {
        $sections = RateCardSection::query()
            ->whereHas('channels', fn ($query) => $query->where('media_properties.id', $mediaPropertyId)->where('media_properties.is_public', true))
            ->whereHas('product', fn ($query) => $query->where('status', 'active'))
            ->with(['channels', 'entries', 'product.mediaProfile'])
            ->orderBy('sort_order')
            ->get();

        return self::present($sections);
    }

    /**
     * @param  Collection<int, RateCardSection>  $sections
     * @return list<array<string, mixed>>
     */
    private static function present(Collection $sections): array
    {
        return $sections->map(function (RateCardSection $section): array {
            $product = $section->product;
            $profile = $product?->getRelationValue('mediaProfile');
            $entries = $section->entries->map(fn ($entry): array => [
                'id' => $entry->id,
                'item' => $entry->item,
                'unit' => $entry->unit,
                'price' => $entry->price_rm === null ? null : (float) $entry->price_rm,
                'price_incl_sst' => $entry->price_incl_sst_rm === null ? null : (float) $entry->price_incl_sst_rm,
                'price_basis' => $entry->price_basis,
                'notes' => $entry->notes,
                'confidence' => $entry->confidence,
                'is_package' => (bool) $entry->is_package,
                'table_key' => $entry->table_key,
                'row_label' => $entry->row_label,
                'column_label' => $entry->column_label,
                'product_slug' => $section->product?->slug,
                'product_name' => $section->product?->name,
            ])->all();

            return [
                'id' => $section->id,
                'key' => $section->key,
                'title' => $section->title,
                'is_media_value' => $section->isMediaValue(),
                'intro' => $section->intro,
                'medium' => $section->medium,
                'channels' => $section->channels->map(fn ($channel): array => [
                    'slug' => $channel->slug,
                    'name' => $channel->name,
                ])->all(),
                'product' => $product === null ? null : [
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'validity' => $profile instanceof MediaProfile ? $profile->validity : null,
                ],
                'entries' => $entries,
                'tables' => self::tables($entries),
            ];
        })->values()->all();
    }

    /**
     * @param  list<array<string, mixed>>  $entries
     * @return list<array<string, mixed>>
     */
    private static function tables(array $entries): array
    {
        $tables = [];

        foreach ($entries as $entry) {
            if (empty($entry['table_key'])) {
                continue;
            }

            $key = $entry['table_key'];
            $column = $entry['column_label'] ?? '';
            $row = $entry['row_label'] ?? '';

            $tables[$key]['key'] = $key;
            $tables[$key]['columns'][$column] = $column;
            $tables[$key]['rows'][$row][$column] = $entry;
        }

        return array_values(array_map(fn (array $table): array => [
            'key' => $table['key'],
            'columns' => array_values(array_filter($table['columns'])),
            'rows' => array_map(
                fn (string $label, array $cells): array => ['label' => $label, 'cells' => $cells],
                array_keys($table['rows']),
                array_values($table['rows']),
            ),
        ], $tables));
    }
}
