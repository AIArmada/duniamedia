<?php

namespace Tests\Feature;

use Tests\TestCase;

class OfficialMediaIntegrityTest extends TestCase
{
    /** @var list<string> */
    private const BRANDED_DECKS = [
        'rtm-tv-radio-2025',
        'bernama-media-kit-2026',
        'omnia-content-terhangat-2026',
        'astro-upfront-2027',
        '988-fm-2026-radio-digital',
        'suria-fm-2026-radio-digital',
        'hot-fm-zoomerz-on-ground',
        'tvalhijrah-bincang-niaga-2024',
        'target-media-elevator-q2-2025',
        'tmr-super-deal-2026-business-content',
        'ra-media-website-media-solutions-2026',
    ];

    public function test_official_gallery_images_have_provenance_and_exist(): void
    {
        $path = resource_path('data/rate-card-official-gallery.json');
        $this->assertFileExists($path);

        $manifest = json_decode((string) file_get_contents($path), true);
        $this->assertIsArray($manifest);

        foreach ($manifest as $slug => $assets) {
            $this->assertNotEmpty($assets, "No official assets for {$slug}");

            foreach ($assets as $asset) {
                $this->assertFileExists(public_path($asset['path']));
                $this->assertNotEmpty($asset['source_url'] ?? null, "Missing source URL for {$asset['path']}");
                $this->assertNotEmpty($asset['source_label'] ?? null, "Missing source label for {$asset['path']}");
                $this->assertNotEmpty($asset['credit'] ?? null, "Missing credit for {$asset['path']}");
                $this->assertSame('official', $asset['kind'] ?? null);
                $this->assertFalse((bool) ($asset['is_illustrative'] ?? true));
            }
        }
    }

    public function test_branded_decks_do_not_contain_generated_gallery_images(): void
    {
        $path = resource_path('data/rate-card-generated-gallery.json');
        $manifest = is_file($path) ? (json_decode((string) file_get_contents($path), true) ?: []) : [];

        $this->assertSame([], array_values(array_intersect(self::BRANDED_DECKS, array_keys($manifest))));
    }
}
