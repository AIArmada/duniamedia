<?php

declare(strict_types=1);

namespace App\Features\Catalog\Support;

use App\Features\Catalog\Models\MediaProfile;
use App\Features\Catalog\Models\MediaProperty;
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
        $property = MediaProperty::query()->find($mediaPropertyId);

        $sections = RateCardSection::query()
            ->whereHas('channels', fn ($query) => $query->where('media_properties.id', $mediaPropertyId)->where('media_properties.is_public', true))
            ->whereHas('product', fn ($query) => $query->where('status', 'active'))
            ->with(['channels', 'entries', 'product.mediaProfile'])
            ->orderBy('sort_order')
            ->get();

        $isGroupFallback = false;

        if ($sections->isEmpty() && $property !== null) {
            $sections = self::groupFallbackSections($property);
            $isGroupFallback = $sections->isNotEmpty();
        }

        $presented = self::present($sections);

        if ($isGroupFallback) {
            foreach ($presented as &$section) {
                $section['is_group_fallback'] = true;
            }
            unset($section);
        }

        return $presented;
    }

    /**
     * @return Collection<int, RateCardSection>
     */
    private static function groupFallbackSections(MediaProperty $property): Collection
    {
        $profiles = $property->decks()->with('channels')->get();
        $productIds = $profiles->pluck('product_id')->filter()->unique()->all();

        if ($productIds === []) {
            return new Collection;
        }

        $sections = RateCardSection::query()
            ->whereIn('product_id', $productIds)
            ->whereHas('channels', fn ($query) => $query->where('media_properties.slug', 'like', '%-group'))
            ->whereHas('product', fn ($query) => $query->where('status', 'active'))
            ->with(['channels', 'entries', 'product.mediaProfile'])
            ->orderBy('sort_order')
            ->get();

        $keywords = match ($property->medium) {
            'Radio', 'Podcast' => ['radio', 'audio', 'ros', 'jingle', 'live read', 'station'],
            'TV & Broadcast' => ['tv', 'spot', 'zone', 'programme', 'sponsor', 'talkshow', 'news', 'filmlet', 'slide', 'capsule', 'crawler', 'clockface', 'on-ground', 'production'],
            'Digital' => ['digital', 'social', 'website', 'display', 'edm', 'press', 'article', 'video', 'content', 'online', 'stream', 'podcast', 'news'],
            'Print' => ['print', 'display', 'classified', 'cover', 'advertorial', 'colour', 'page', 'newspaper', 'magazine'],
            'Outdoor' => ['outdoor', 'billboard', 'site', 'panel', 'station', 'airport', 'venue', 'activation', 'truck', 'bus', 'lamppost', 'screen'],
            default => [],
        };

        $filtered = $sections->filter(function (RateCardSection $section) use ($keywords): bool {
            $haystack = mb_strtolower($section->key.' '.$section->title.' '.($section->intro ?? ''));

            foreach ($keywords as $keyword) {
                if (str_contains($haystack, $keyword)) {
                    return true;
                }
            }

            return false;
        });

        if ($filtered->isNotEmpty()) {
            return $filtered->values();
        }

        $deckMediums = $profiles
            ->flatMap(fn (MediaProfile $profile) => $profile->channels->pluck('medium'))
            ->filter()
            ->unique();

        if ($deckMediums->count() === 1 && $deckMediums->first() === $property->medium) {
            return $sections;
        }

        return new Collection;
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
                'price' => $entry->price_rm !== null
                    ? (float) $entry->price_rm
                    : ($entry->price_incl_sst_rm === null ? null : (float) $entry->price_incl_sst_rm),
                'price_incl_sst' => $entry->price_incl_sst_rm === null ? null : (float) $entry->price_incl_sst_rm,
                'price_is_inclusive' => $entry->price_rm === null && $entry->price_incl_sst_rm !== null,
                'price_basis' => $entry->price_basis,
                'notes' => $entry->notes,
                'confidence' => $entry->confidence,
                'is_package' => (bool) $entry->is_package,
                'selectable' => (bool) $entry->is_plan_selectable,
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
