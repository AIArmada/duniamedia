<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesMediaOffers;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use CreatesMediaOffers;
    use RefreshDatabase;

    public function test_public_pages_are_accessible(): void
    {
        $this->get('/')->assertOk()->assertSee('Bigger');
        $this->get('/services')->assertOk();
        $this->get('/portfolio')->assertOk();
        $this->get('/work')->assertOk()->assertSee('Work that speaks for');
    }

    public function test_unpublished_inventory_is_not_public(): void
    {
        $this->createMediaOffer([
            'name' => 'Private Test',
            'slug' => 'private-test',
            'status' => 'draft',
        ]);

        $this->get('/offers/private-test')->assertNotFound();
        $this->get('/work/media/not-a-real-file')->assertNotFound();
    }

    public function test_quotation_requests_are_not_bookings(): void
    {
        $this->assertDatabaseCount('quotes', 0);
        $this->assertDatabaseCount('enquiries', 0);
        $this->assertDatabaseCount('bookings', 0);
    }
}
