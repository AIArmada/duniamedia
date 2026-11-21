<?php

declare(strict_types=1);

namespace Tests\Feature;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\EntryPoints\Filament\ProductResource\Pages\CreateProduct as CreateProductPage;
use App\Features\Catalog\EntryPoints\Filament\ProductResource\Pages\EditProduct as EditProductPage;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Clients\Models\Campaign;
use App\Features\Clients\Models\ClientReference;
use App\Features\Identity\Models\Party;
use App\Features\Identity\Models\PartyRole;
use App\Features\Identity\Models\User;
use App\Features\Sales\Actions\AcceptQuote;
use App\Features\Sales\Models\Enquiry;
use App\Features\Sales\Models\Quote;
use App\Features\Sales\Models\QuoteLine;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\TaxSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesMediaOffers;
use Tests\TestCase;

class ConversionRegressionsTest extends TestCase
{
    use CreatesMediaOffers;
    use RefreshDatabase;

    public function test_work_explorer_filters_by_client_slug_and_sector(): void
    {
        $government = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'Kementerian Contoh',
            'slug' => 'kementerian-contoh',
            'status' => 'active',
        ]);

        $government->organization()->create(['organization_type' => 'government']);

        ClientReference::query()->create([
            'client_party_id' => $government->id,
            'reference_type' => 'client_logo',
            'permission_granted' => true,
            'public_label' => 'Kementerian Contoh',
        ]);

        $this->campaignFor($government, 'Kempen Kebangsaan', 'kempen-kebangsaan');
        $this->campaignFor($government, 'Kempen Kesihatan', 'kempen-kesihatan');

        $this->get('/work?client=kementerian-contoh')
            ->assertOk()
            ->assertSee('Kempen Kebangsaan');

        $this->get('/work?sector=government')
            ->assertOk()
            ->assertSee('Kempen Kebangsaan');
    }

    public function test_work_explorer_searches_campaigns_by_client_name(): void
    {
        $client = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'Tenaga Nasional Berhad',
            'slug' => 'tenaga-nasional',
            'status' => 'active',
        ]);

        ClientReference::query()->create([
            'client_party_id' => $client->id,
            'reference_type' => 'client_logo',
            'permission_granted' => true,
        ]);

        $this->campaignFor($client, 'Hari Raya Televisyen', 'hari-raya-televisyen');

        $this->get('/work?q=Tenaga')
            ->assertOk()
            ->assertSee('Hari Raya Televisyen');
    }

    public function test_global_search_matches_media_owner_and_campaign_client_name(): void
    {
        $owner = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'Pemilik Media Nusantara',
            'slug' => 'pemilik-media-nusantara',
            'status' => 'active',
        ]);

        $this->createMediaOffer([
            'name' => 'Luar Bandar Billboard',
            'slug' => 'luar-bandar-billboard',
        ], [
            'medium' => 'Outdoor',
            'media_owner_party_id' => $owner->id,
        ]);

        $client = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'Syarikat Klien Istimewa',
            'slug' => 'syarikat-klien-istimewa',
            'status' => 'active',
        ]);

        $this->campaignFor($client, 'Kempen Klien Istimewa', 'kempen-klien-istimewa');

        $this->get('/search?q=Nusantara')
            ->assertOk()
            ->assertSee('Luar Bandar Billboard');

        $this->get('/search?q=Istimewa')
            ->assertOk()
            ->assertSee('Kempen Klien Istimewa');
    }

    public function test_accepting_a_quote_requires_an_authorized_actor(): void
    {
        $this->seed(TaxSeeder::class);

        $seller = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'RA Media Relations Sdn Bhd',
            'slug' => 'ra-media-relations',
            'status' => 'active',
        ]);

        $buyer = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'Buyer',
            'slug' => 'buyer',
            'status' => 'active',
        ]);

        $quote = Quote::query()->create([
            'seller_party_id' => $seller->id,
            'buyer_party_id' => $buyer->id,
            'quote_number' => 'DM-AUTH-TEST',
            'currency_code' => 'MYR',
            'status' => 'requested',
            'price_visibility' => 'private',
            'valid_until' => now()->addDays(14)->toDateString(),
        ]);

        QuoteLine::query()->create([
            'quote_id' => $quote->id,
            'kind' => 'media',
            'description' => 'Unauthorised line',
            'quantity' => 1,
            'unit_price' => 100,
            'line_total' => 100,
        ]);

        $this->expectException(AuthorizationException::class);

        app(AcceptQuote::class)->execute($quote->id, $buyer->id);
    }

    public function test_accepting_a_quote_is_idempotent_for_an_authorized_seller(): void
    {
        $this->seed(TaxSeeder::class);

        $seller = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'RA Media Relations Sdn Bhd',
            'slug' => 'ra-media-relations',
            'status' => 'active',
        ]);

        PartyRole::query()->create([
            'party_id' => $seller->id,
            'role_code' => 'seller',
            'valid_from' => now()->toDateString(),
        ]);

        $buyer = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'Buyer',
            'slug' => 'buyer',
            'status' => 'active',
        ]);

        $quote = Quote::query()->create([
            'seller_party_id' => $seller->id,
            'buyer_party_id' => $buyer->id,
            'quote_number' => 'DM-IDEMPOTENT',
            'currency_code' => 'MYR',
            'status' => 'requested',
            'price_visibility' => 'private',
            'valid_until' => now()->addDays(14)->toDateString(),
        ]);

        QuoteLine::query()->create([
            'quote_id' => $quote->id,
            'kind' => 'media',
            'description' => 'Media line',
            'quantity' => 1,
            'unit_price' => 100,
            'line_total' => 100,
        ]);

        $first = app(AcceptQuote::class)->execute($quote->id, $seller->id);
        $second = app(AcceptQuote::class)->execute($quote->id, $seller->id);

        $this->assertSame($first->id, $second->id);
    }

    public function test_admin_can_open_quote_enquiry_and_media_property_forms(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $admin->assignRole('super_admin');

        $this->actingAs($admin);

        foreach ([
            '/admin/quotes/create',
            '/admin/enquiries/create',
            '/admin/media-properties/create',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_admin_can_edit_existing_quote_enquiry_and_media_property_records(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $admin->assignRole('super_admin');

        $this->actingAs($admin);

        $party = Party::query()->create([
            'party_type' => 'organization',
            'display_name' => 'Editable Party',
            'slug' => 'editable-party',
            'status' => 'active',
        ]);

        $quote = Quote::query()->create([
            'seller_party_id' => $party->id,
            'buyer_party_id' => $party->id,
            'quote_number' => 'DM-EDIT-TEST',
            'currency_code' => 'MYR',
            'status' => 'requested',
            'price_visibility' => 'private',
        ]);

        $enquiry = Enquiry::query()->create([
            'requester_party_id' => $party->id,
            'source' => 'website',
            'subject' => 'Editable enquiry',
            'status' => 'new',
        ]);

        $property = MediaProperty::query()->create([
            'owner_party_id' => $party->id,
            'property_type' => 'channel',
            'name' => 'Editable property',
            'slug' => 'editable-property',
            'status' => 'active',
        ]);

        $this->get('/admin/quotes/'.$quote->getRouteKey())->assertOk();
        $this->get('/admin/enquiries/'.$enquiry->getRouteKey())->assertOk();
        $this->get('/admin/media-properties/'.$property->getRouteKey())->assertOk();
    }

    public function test_product_admin_page_saves_its_media_profile_through_the_catalog_owner(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $admin->assignRole('super_admin');

        $this->actingAs($admin);

        Livewire::test(CreateProductPage::class)
            ->set('data.name', 'Page Product')
            ->set('data.slug', 'page-product')
            ->set('data.type', 'service')
            ->set('data.status', 'active')
            ->set('data.visibility', 'catalog_search')
            ->set('data.medium', 'Outdoor')
            ->set('data.unit_code', 'month')
            ->set('data.pricing_mode', 'fixed')
            ->set('data.availability_status', 'available')
            ->call('create')
            ->assertHasNoFormErrors();

        $product = Product::query()->where('slug', 'page-product')->firstOrFail();

        $this->assertSame('Outdoor', $product->mediaProfile?->medium);

        Livewire::test(EditProductPage::class, ['record' => $product->getRouteKey()])
            ->assertSet('data.medium', 'Outdoor')
            ->set('data.medium', 'Radio')
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Radio', $product->mediaProfile()->first()?->medium);
    }

    private function campaignFor(Party $client, string $name, string $slug): Campaign
    {
        return Campaign::query()->create([
            'client_party_id' => $client->id,
            'advertiser_party_id' => $client->id,
            'name' => $name,
            'campaign_code' => $slug,
            'slug' => $slug,
            'medium' => 'TV & Broadcast',
            'channel_label' => 'Television',
            'status' => 'completed',
            'starts_on' => now()->startOfYear()->toDateString(),
            'published_at' => now(),
        ]);
    }
}
