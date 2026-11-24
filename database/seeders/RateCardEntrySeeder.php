<?php

namespace Database\Seeders;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\MediaProfile;
use App\Features\Catalog\Persistence\WriteRateCardEntries;
use App\Features\Catalog\Persistence\WriteRateCardNotes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class RateCardEntrySeeder extends Seeder
{
    public function run(): void
    {
        $path = resource_path('data/rate-list-2026.json');

        if (! is_file($path)) {
            return;
        }

        $data = json_decode((string) file_get_contents($path), true);
        $products = Product::query()->pluck('id', 'slug')->all();
        $profiles = MediaProfile::query()->pluck('id', 'product_id')->all();
        $now = now();
        $entries = [];
        $sourceSlugs = [];
        $unknown = [];

        foreach ($data['rates'] ?? [] as $rate) {
            $source = (string) $rate['source_file'];
            $slugs = $this->slugs($rate['offering_slugs'] ?? '');

            foreach ($slugs as $slug) {
                $sourceSlugs[$source][$slug] = true;
            }

            foreach ($slugs as $slug) {
                $productId = $products[$slug] ?? null;

                if ($productId === null) {
                    $unknown[$slug] = true;

                    continue;
                }

                $entries[] = [
                    'id' => (string) Str::uuid7(),
                    'product_id' => $productId,
                    'media_profile_id' => $profiles[$productId] ?? null,
                    'source_file' => $source,
                    'page' => (int) ($rate['page'] ?? 1),
                    'section' => $rate['section'] ?? null,
                    'item' => (string) ($rate['item'] ?? ''),
                    'unit' => $rate['unit'] ?? null,
                    'price_rm' => $rate['price_rm'] ?? null,
                    'price_incl_sst_rm' => $rate['price_incl_sst_rm'] ?? null,
                    'price_basis' => $rate['price_basis'] ?? null,
                    'confidence' => $rate['confidence'] ?? null,
                    'notes' => $rate['notes'] ?? null,
                    'source_text' => $rate['source_text'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if ($unknown !== []) {
            throw new RuntimeException('Rate-card ledger references unknown offerings: '.implode(', ', array_keys($unknown)));
        }

        $notes = [];

        foreach ($data['gaps'] ?? [] as $gap) {
            $source = $gap['source_file'] ?? null;
            $payload = $gap['gap'] ?? null;

            [$page, $note, $raw] = $this->gapPayload($payload);
            $productIds = $this->productIdsForSource($sourceSlugs, $products, $source);

            foreach ($productIds as $productId) {
                $notes[] = $this->noteRow($productId, $source, $page, 'gap', $note, $raw, $now);
            }
        }

        foreach ($data['missing_documents'] ?? [] as $document) {
            $productId = $products[$document['offering'] ?? ''] ?? null;
            $notes[] = $this->noteRow(
                $productId,
                null,
                null,
                'missing_document',
                (string) ($document['reason'] ?? 'No source document.'),
                $document['title'] ?? null,
                $now,
            );
        }

        $entryCount = app(WriteRateCardEntries::class)->replaceAll($entries);
        $noteCount = app(WriteRateCardNotes::class)->replaceAll($notes);

        $this->command?->info("Seeded {$entryCount} rate-card entries and {$noteCount} extraction notes.");
    }

    /**
     * @return list<string>
     */
    private function slugs(string $value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    /**
     * @param  array<string, array<string, bool>>  $sourceSlugs
     * @param  array<string, string>  $products
     * @return list<string|null>
     */
    private function productIdsForSource(array $sourceSlugs, array $products, ?string $source): array
    {
        $productIds = [];

        foreach (array_keys($sourceSlugs[$source] ?? []) as $slug) {
            if (isset($products[$slug])) {
                $productIds[] = $products[$slug];
            }
        }

        return $productIds === [] ? [null] : $productIds;
    }

    /**
     * @return array{0: int|null, 1: string, 2: string|null}
     */
    private function gapPayload(mixed $payload): array
    {
        if (is_array($payload)) {
            return [
                isset($payload['page']) ? (int) $payload['page'] : null,
                (string) ($payload['note'] ?? $payload['raw'] ?? ''),
                $payload['raw'] ?? null,
            ];
        }

        return [null, (string) $payload, null];
    }

    /**
     * @return array<string, mixed>
     */
    private function noteRow(
        ?string $productId,
        ?string $source,
        ?int $page,
        string $kind,
        string $note,
        ?string $raw,
        mixed $now,
    ): array {
        return [
            'id' => (string) Str::uuid7(),
            'product_id' => $productId,
            'source_file' => $source,
            'page' => $page,
            'kind' => $kind,
            'note' => $note,
            'raw' => $raw,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
