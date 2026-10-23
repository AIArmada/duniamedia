<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{MediaListing,Lead};
class WebsiteTest extends TestCase {
 use RefreshDatabase;
 public function test_public_pages_are_accessible():void {$this->get('/')->assertOk()->assertSee('Bigger');$this->get('/services')->assertOk();$this->get('/portfolio')->assertOk();}
 public function test_unpublished_inventory_is_not_public():void {MediaListing::create(['title'=>'Private Test','slug'=>'private-test','type'=>'Outdoor','summary'=>'Not public','is_published'=>false]);$this->get('/media/private-test')->assertNotFound();}
 public function test_quotation_requests_are_not_bookings():void {$this->assertDatabaseCount('quotes',0);$this->assertDatabaseCount('leads',0);}
}
