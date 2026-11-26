<?php

namespace Database\Seeders;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Persistence\WriteRateCardAssets;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class RateCardGallerySeeder extends Seeder
{
    public function run(): void
    {
        $path = resource_path('data/rate-card-gallery.json');

        if (! is_file($path)) {
            return;
        }

        $manifest = json_decode((string) file_get_contents($path), true) ?: [];
        $officialPath = resource_path('data/rate-card-official-gallery.json');
        $official = is_file($officialPath)
            ? (json_decode((string) file_get_contents($officialPath), true) ?: [])
            : [];
        $generatedPath = resource_path('data/rate-card-generated-gallery.json');
        $generated = is_file($generatedPath)
            ? (json_decode((string) file_get_contents($generatedPath), true) ?: [])
            : [];

        foreach ($official as $slug => $assets) {
            $manifest[$slug] = array_merge($assets, $manifest[$slug] ?? []);
        }

        foreach ($generated as $slug => $assets) {
            $manifest[$slug] = array_merge($manifest[$slug] ?? [], $assets);
        }

        $products = Product::query()->get()->keyBy('slug');
        $writer = app(WriteRateCardAssets::class);
        $total = 0;

        foreach ($manifest as $slug => $assets) {
            $product = $products->get($slug);

            if ($product === null) {
                continue;
            }

            $rows = [];

            foreach ($assets as $order => $asset) {
                if (! is_file(public_path($asset['path']))) {
                    throw new RuntimeException("Gallery asset is missing: {$asset['path']}");
                }

                $rows[] = [
                    'id' => (string) Str::uuid7(),
                    'product_id' => $product->id,
                    'media_property_id' => null,
                    'path' => $asset['path'],
                    'kind' => $asset['kind'] ?? 'photo',
                    'alt' => $asset['alt'] ?? $slug,
                    'caption' => $asset['caption'] ?? null,
                    'source_url' => $asset['source_url'] ?? null,
                    'source_label' => $asset['source_label'] ?? null,
                    'credit' => $asset['credit'] ?? null,
                    'is_illustrative' => (bool) ($asset['is_illustrative'] ?? (($asset['kind'] ?? null) === 'generated')),
                    'width' => (int) ($asset['width'] ?? 0),
                    'height' => (int) ($asset['height'] ?? 0),
                    'bytes' => (int) ($asset['bytes'] ?? 0),
                    'sort_order' => $order,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $total += $writer->replaceForProduct($product, $rows);
        }

        $this->command?->info("Seeded {$total} official and source-deck gallery images.");
    }
}
