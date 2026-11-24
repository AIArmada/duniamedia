<?php

namespace Tests\Feature;

use Database\Seeders\MediaCatalogueSeeder;
use Database\Seeders\RateCardContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicNoPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_never_expose_pdf_or_deck_documents(): void
    {
        $this->seed(MediaCatalogueSeeder::class);
        $this->seed(RateCardContentSeeder::class);

        $response = $this->get('/offers/988-fm-2026-radio-digital');

        $response->assertOk();
        $response->assertDontSee('.pdf', false);
        $response->assertDontSee('rate-card/', false);
        $response->assertDontSee('source document', false);
        $response->assertDontSee('<iframe', false);

        $this->get('/offers/988-fm-2026-radio-digital/rate-card/0')->assertNotFound();
        $this->get('/media/owners/media-prima')->assertOk()->assertDontSee('.pdf', false);
        $this->get('/media/channels/media-prima-tv3')->assertOk()->assertDontSee('.pdf', false);
    }
}
