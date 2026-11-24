<?php

namespace Tests\Feature;

use Tests\TestCase;

class RateCardCoverageTest extends TestCase
{
    /**
     * The 2024 KLIA decks were replaced by the 2026 static and digital cards.
     *
     * @var list<string>
     */
    private const SUPERSEDED = [
        'klia-t1-airport-2024-premium.pdf',
        'klia-t2-airport-2024.pdf',
    ];

    /**
     * @var list<string>
     */
    private const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png', 'xlsx', 'mp4'];

    public function test_every_catalogue_document_resolves_to_a_stored_file(): void
    {
        foreach ($this->catalogue() as $offering) {
            foreach ($offering['documents'] ?? [] as $document) {
                $path = storage_path('app/'.$document['path']);

                $this->assertFileExists($path, "{$offering['slug']} references a missing document.");
                $this->assertContains(
                    strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                    self::ALLOWED_EXTENSIONS,
                    "{$offering['slug']} references an unsupported document type.",
                );
            }
        }
    }

    public function test_every_source_pdf_is_referenced_by_an_offering(): void
    {
        $referenced = [];

        foreach ($this->catalogue() as $offering) {
            foreach ($offering['documents'] ?? [] as $document) {
                $referenced[basename($document['file'])] = true;
            }
        }

        foreach ($this->sourcePdfs() as $name) {
            $attached = isset($referenced[$name]);
            $superseded = in_array($name, self::SUPERSEDED, true);

            $this->assertTrue(
                $attached || $superseded,
                "{$name} is neither attached to an offering nor listed as superseded.",
            );
        }
    }

    public function test_every_source_pdf_has_extracted_rate_rows(): void
    {
        $ledger = json_decode((string) file_get_contents(resource_path('data/rate-list-2026.json')), true);
        $sources = array_values(array_unique(array_column($ledger['rates'], 'source_file')));

        foreach ($this->sourcePdfs() as $name) {
            if (in_array($name, self::SUPERSEDED, true)) {
                continue;
            }

            $this->assertContains($name, $sources, "{$name} has no extracted rate rows.");
        }
    }

    public function test_every_pdf_backed_offering_has_extracted_display_copy(): void
    {
        foreach ($this->catalogue() as $offering) {
            if (empty($offering['documents'])) {
                continue;
            }

            $specs = $offering['specifications'] ?? [];

            $this->assertNotEmpty($offering['summary'], "{$offering['slug']} has no summary.");
            $this->assertNotEmpty($offering['description'], "{$offering['slug']} has no description.");
            $this->assertNotEmpty($specs['formats'] ?? null, "{$offering['slug']} has no extracted formats.");
            $this->assertNotEmpty($specs['source_file'] ?? null, "{$offering['slug']} has no source reference.");

            $this->assertTrue(
                ! empty($specs['price_tiers'] ?? null) || ($specs['is_reference'] ?? false),
                "{$offering['slug']} has no extracted rate tiers.",
            );

            foreach (['View original', 'request rate tables', 'no extractable text'] as $marker) {
                $this->assertStringNotContainsStringIgnoringCase($marker, (string) $offering['description']);
            }
        }
    }

    public function test_every_pdf_backed_offering_has_a_hero_image(): void
    {
        foreach ($this->catalogue() as $offering) {
            if (empty($offering['documents'])) {
                continue;
            }

            $path = $offering['image_path'] ?? 'assets/ratecards/img/'.$offering['slug'].'.webp';

            $this->assertFileExists(public_path($path), "{$offering['slug']} has no hero image.");
        }
    }

    public function test_asset_catalogue_documents_exist(): void
    {
        $data = json_decode((string) file_get_contents(resource_path('data/ratecards-2026.json')), true);

        foreach ($data['offerings'] as $offering) {
            foreach ($offering['specifications']['documents'] ?? [] as $document) {
                $file = $document['file'];
                $path = storage_path('app/private/ratecards/'.$file);

                if (! is_file($path)) {
                    $path = storage_path('app/private/ratecards/video/'.basename($file));
                }

                $this->assertFileExists($path, "{$offering['slug']} references a missing asset document.");
            }
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function catalogue(): array
    {
        $data = json_decode((string) file_get_contents(resource_path('data/media-catalogue-2026.json')), true);

        return $data['offerings'];
    }

    /**
     * @return list<string>
     */
    private function sourcePdfs(): array
    {
        return array_map(
            'basename',
            glob(storage_path('app/private/ratecards/*.pdf')) ?: [],
        );
    }
}
