<?php

namespace Database\Seeders;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\MediaProfile;
use App\Features\Catalog\Persistence\WriteDeckChannels;
use App\Features\Catalog\Persistence\WriteMediaProfiles;
use App\Features\Catalog\Persistence\WriteMediaProperties;
use App\Features\Catalog\Persistence\WriteRateCardAssets;
use App\Features\Catalog\Persistence\WriteRateCardBlocks;
use App\Features\Catalog\Persistence\WriteRateCardEntries;
use App\Features\Catalog\Persistence\WriteRateCardSections;
use App\Features\Identity\Persistence\WriteParties;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class RateCardContentSeeder extends Seeder
{
    public function run(): void
    {
        $files = glob(resource_path('data/rate-cards/*.json')) ?: [];

        if ($files === []) {
            return;
        }

        $products = Product::query()->pluck('id', 'slug')->all();
        $profiles = MediaProfile::query()->pluck('id', 'product_id')->all();
        $parties = app(WriteParties::class);
        $properties = app(WriteMediaProperties::class);
        $mediaProfiles = app(WriteMediaProfiles::class);
        $deckChannels = app(WriteDeckChannels::class);
        $sections = app(WriteRateCardSections::class);
        $entries = app(WriteRateCardEntries::class);
        $assets = app(WriteRateCardAssets::class);
        $blocks = app(WriteRateCardBlocks::class);

        $counts = ['decks' => 0, 'channels' => 0, 'sections' => 0, 'entries' => 0, 'assets' => 0];

        foreach ($files as $file) {
            $data = json_decode((string) file_get_contents($file), true);

            if (! is_array($data) || empty($data['slug'])) {
                throw new RuntimeException("Invalid rate-card content file: {$file}");
            }

            $product = Product::query()->where('slug', $data['slug'])->first();

            if ($product === null) {
                throw new RuntimeException("Rate-card content references unknown product: {$data['slug']}");
            }

            $profile = MediaProfile::query()->where('product_id', $product->id)->first();

            if ($profile === null) {
                throw new RuntimeException("Rate-card content references a product without a media profile: {$data['slug']}");
            }

            $channelIds = [];
            $channelBySlug = [];

            foreach ($data['channels'] ?? [] as $index => $channel) {
                $owner = $parties->findBySlug((string) $channel['owner'])
                    ?? $parties->party(
                        ['slug' => (string) $channel['owner']],
                        [
                            'party_type' => 'organization',
                            'display_name' => Str::headline((string) $channel['owner']),
                            'status' => 'active',
                        ],
                    );

                $property = $properties->upsert(
                    ['slug' => $channel['slug']],
                    [
                        'owner_party_id' => $owner->id,
                        'property_type' => 'channel',
                        'name' => $channel['name'],
                        'status' => 'active',
                        'description' => $channel['description'] ?? null,
                        'medium' => $channel['medium'] ?? $data['medium'] ?? null,
                        'category' => $channel['category'] ?? $channel['medium'] ?? 'Channel',
                        'audience' => $channel['audience'] ?? null,
                        'stats' => ($channel['stats'] ?? []) ?: null,
                        'hero_path' => $channel['hero'] ?? $data['hero'] ?? null,
                        'is_public' => true,
                        'sort_order' => $index,
                    ],
                );

                $channelIds[] = $property->id;
                $channelBySlug[$channel['slug']] = $property;
                $counts['channels']++;
            }

            if ($channelIds === []) {
                throw new RuntimeException("Rate-card content has no channels: {$data['slug']}");
            }

            $mediaProfiles->update($profile, [
                'validity' => $data['validity'] ?? null,
                'terms' => $data['terms'] ?? $profile->terms,
                'stats' => ($data['stats'] ?? []) ?: null,
                'highlights' => $data['highlights'] ?? null,
            ]);

            $deckChannels->replace($profile, $channelIds);

            $sectionRows = [];
            $sectionItems = [];

            $appendSection = function (array $section, array $defaultChannels) use (&$sectionRows, &$sectionItems): void {
                $key = (string) $section['key'];

                if (isset($sectionItems[$key])) {
                    throw new RuntimeException("Duplicate rate-card section key: {$key}");
                }

                $sectionRows[] = [
                    'key' => $key,
                    'title' => $section['title'] ?? $key,
                    'intro' => $section['intro'] ?? null,
                    'medium' => $section['medium'] ?? null,
                    'channels' => $section['channels'] ?? $defaultChannels,
                ];
                $sectionItems[$key] = $section['items'] ?? [];
            };

            foreach ($data['shared_sections'] ?? [] as $section) {
                $appendSection($section, []);
            }

            foreach ($data['channels'] as $channel) {
                foreach ($channel['sections'] ?? [] as $section) {
                    $appendSection($section, [$channel['slug']]);
                }
            }

            $entryRows = [];
            $sectionModels = [];

            if ($sectionRows !== []) {
                $resolved = [];

                foreach ($sectionRows as $index => $row) {
                    $ids = [];

                    foreach ($row['channels'] as $channelSlug) {
                        if (! isset($channelBySlug[$channelSlug])) {
                            throw new RuntimeException("Rate-card section {$row['key']} references unknown channel: {$channelSlug}");
                        }

                        $ids[] = $channelBySlug[$channelSlug]->id;
                    }

                    if ($ids === []) {
                        throw new RuntimeException("Rate-card section {$row['key']} has no channels.");
                    }

                    $resolved[] = [
                        'key' => $row['key'],
                        'title' => $row['title'],
                        'intro' => $row['intro'],
                        'medium' => $row['medium'],
                        'channel_ids' => array_values(array_unique($ids)),
                        'sort_order' => $index,
                    ];
                }

                $sectionModels = $sections->replaceForProduct($product, $resolved);
            }

            foreach ($sectionModels as $key => $section) {
                foreach ($sectionItems[$key] as $index => $item) {
                    $entryRows[] = $this->entryRow($product, $profile, $section->id, $item, $index, $data);
                }
            }

            $counts['sections'] += count($sectionModels);
            $counts['entries'] += $entries->replaceForProduct($product, $entryRows);
            $counts['decks']++;

            $assetRows = [];

            foreach ($data['assets'] ?? [] as $index => $asset) {
                $assetRows[] = $this->assetRow($product, null, $asset, $index);
            }

            foreach ($data['channels'] as $channel) {
                $property = $channelBySlug[$channel['slug']];

                foreach ($channel['assets'] ?? [] as $index => $asset) {
                    $assetRows[] = $this->assetRow($product, $property->id, $asset, $index);
                }

                $blocks->replaceFor($property, $channel['blocks'] ?? []);
            }

            $counts['assets'] += $assets->replaceForProduct($product, $assetRows);
            $blocks->replaceFor($product, $data['blocks'] ?? []);
        }

        $this->command?->info(sprintf(
            'Seeded %d decks, %d channels, %d sections, %d rate entries and %d assets.',
            $counts['decks'],
            $counts['channels'],
            $counts['sections'],
            $counts['entries'],
            $counts['assets'],
        ));
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function entryRow(Product $product, MediaProfile $profile, string $sectionId, array $item, int $index, array $data): array
    {
        return [
            'id' => (string) Str::uuid7(),
            'product_id' => $product->id,
            'media_profile_id' => $profile->id,
            'section_id' => $sectionId,
            'source_file' => $data['slug'],
            'page' => (int) ($item['page'] ?? 1),
            'table_key' => $item['table_key'] ?? null,
            'row_label' => $item['row_label'] ?? null,
            'column_label' => $item['column_label'] ?? null,
            'item' => $item['item'],
            'unit' => $item['unit'] ?? null,
            'price_rm' => $item['price'] ?? null,
            'price_incl_sst_rm' => $item['price_incl_sst'] ?? null,
            'price_basis' => $item['price_basis'] ?? 'printed',
            'confidence' => $item['confidence'] ?? 'high',
            'notes' => $item['notes'] ?? null,
            'source_text' => $item['source_text'] ?? null,
            'is_package' => (bool) ($item['is_package'] ?? false),
            'validity' => $item['validity'] ?? ($data['validity'] ?? null),
            'terms' => $item['terms'] ?? null,
            'sort_order' => $item['sort_order'] ?? $index,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * @param  array<string, mixed>  $asset
     * @return array<string, mixed>
     */
    private function assetRow(Product $product, ?string $channelId, array $asset, int $index): array
    {
        $path = (string) $asset['path'];

        if (! is_file(public_path($path))) {
            throw new RuntimeException("Rate-card asset is missing: {$path}");
        }

        return [
            'id' => (string) Str::uuid7(),
            'product_id' => $product->id,
            'media_property_id' => $channelId,
            'path' => $path,
            'kind' => $asset['kind'] ?? 'photo',
            'alt' => $asset['alt'] ?? $product->name,
            'caption' => $asset['caption'] ?? null,
            'width' => (int) ($asset['width'] ?? 0),
            'height' => (int) ($asset['height'] ?? 0),
            'sort_order' => $asset['sort_order'] ?? $index,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
