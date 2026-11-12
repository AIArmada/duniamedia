<?php

namespace Tests\Feature;

use App\Livewire\AssetOwnerForm;
use App\Livewire\CampaignPlanner;
use App\Livewire\ContactForm;
use App\Livewire\CreatorJoin;
use App\Livewire\MediaExplorer;
use App\Livewire\QuoteBuilder;
use App\Models\CreatorProfile;
use App\Models\MediaOffering;
use App\Models\Party;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublicExperienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_routes_render_the_redesigned_experience(): void
    {
        $media = MediaOffering::factory()->create([
            'name' => 'Test Billboard',
            'slug' => 'test-billboard',
            'offering_type' => 'Outdoor',
            'location_label' => 'Klang Valley',
            'summary' => 'A test outdoor opportunity.',
        ]);

        foreach ([
            '/',
            '/media',
            '/media/test-billboard',
            '/media/owners',
            '/media/owners/media-prima',
            '/media/owners/media-prima/tv3',
            '/search',
            '/search?q=test',
            '/campaign-plans',
            '/media-advisor',
            '/quote-builder',
            '/creators',
            '/creators/join',
            '/services',
            '/solutions',
            '/portfolio',
            '/work',
            '/academy',
            '/insights',
            '/about',
            '/contact',
            '/list-your-space',
            '/privacy',
            '/terms',
        ] as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/media/test-billboard')->assertSee($media->name);
    }

    public function test_global_search_finds_media_and_creators(): void
    {
        MediaOffering::factory()->create([
            'name' => 'Searchable Billboard',
            'slug' => 'searchable-billboard',
        ]);

        $party = Party::factory()->create(['display_name' => 'Searchable Creator']);
        CreatorProfile::factory()->create([
            'creator_party_id' => $party->id,
            'slug' => 'searchable-creator',
        ]);

        $this->get('/search?q=Searchable')
            ->assertOk()
            ->assertSee('Searchable Billboard')
            ->assertSee('Searchable Creator');
    }

    public function test_media_explorer_filters_and_clears_from_url_state(): void
    {
        MediaOffering::factory()->create(['name' => 'Radio One', 'slug' => 'radio-one', 'offering_type' => 'Radio']);
        MediaOffering::factory()->create(['name' => 'Outdoor One', 'slug' => 'outdoor-one', 'offering_type' => 'Outdoor']);

        Livewire::test(MediaExplorer::class)
            ->set('type', 'Radio')
            ->assertSee('Radio One')
            ->assertDontSee('Outdoor One')
            ->call('clearFilters')
            ->assertSet('type', 'all')
            ->assertSet('vendor', 'all')
            ->assertSet('budget', 'any');
    }

    public function test_media_explorer_filters_by_platform_and_media_owner(): void
    {
        $owner = Party::factory()->create([
            'slug' => 'test-media-owner',
            'display_name' => 'Test Media Owner',
        ]);

        MediaOffering::factory()->create([
            'name' => 'Owner Billboard',
            'slug' => 'owner-billboard',
            'offering_type' => 'Outdoor',
            'seller_party_id' => $owner->id,
        ]);

        Livewire::test(MediaExplorer::class)
            ->set('owner', 'test-media-owner')
            ->assertSee('Owner Billboard')
            ->set('platform', 'digital')
            ->assertDontSee('Owner Billboard');
    }

    public function test_quote_builder_creates_a_request_not_a_booking(): void
    {
        Party::factory()->create(['slug' => 'ra-media-relations', 'display_name' => 'RA Media Relations Sdn Bhd']);

        $media = MediaOffering::factory()->create([
            'name' => 'Test Radio Spot',
            'slug' => 'test-radio-spot',
            'offering_type' => 'Radio',
            'pricing_mode' => 'on_request',
        ]);

        Livewire::test(QuoteBuilder::class)
            ->call('add', $media->id)
            ->set('name', 'Test Buyer')
            ->set('email', 'buyer@example.com')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('reference', fn ($reference) => str_starts_with($reference, 'DM-'));

        $this->assertDatabaseHas('quotes', ['status' => 'requested']);
        $this->assertDatabaseCount('enquiries', 1);
        $this->assertDatabaseCount('quote_lines', 1);
    }

    public function test_advisor_returns_an_illustrative_direction_and_saves_a_brief(): void
    {
        Livewire::test(CampaignPlanner::class)
            ->set('objective', 'leads')
            ->set('budget', '15000-50000')
            ->set('location', 'Klang Valley')
            ->call('plan')
            ->assertSet('showResult', true)
            ->set('name', 'Test Planner')
            ->set('email', 'planner@example.com')
            ->set('consent', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('enquiries', ['source' => 'media_advisor']);
    }

    public function test_contact_form_saves_a_consented_enquiry(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'Test Contact')
            ->set('email', 'contact@example.com')
            ->set('message', 'We would like to discuss a campaign for a new product launch.')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('enquiries', ['source' => 'contact']);
    }

    public function test_creator_application_creates_a_pending_creator_profile(): void
    {
        Livewire::test(CreatorJoin::class)
            ->set('name', 'New Creator')
            ->set('email', 'creator@example.com')
            ->set('handle', '@newcreator')
            ->set('platform', 'TikTok')
            ->set('niche', 'Travel')
            ->set('followers', 12000)
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('creator_profiles', ['verification_status' => 'pending', 'status' => 'pending']);
        $this->assertDatabaseCount('creator_accounts', 1);
    }

    public function test_media_space_submission_creates_a_prospect_asset_and_enquiry(): void
    {
        Livewire::test(AssetOwnerForm::class)
            ->set('name', 'Space Owner')
            ->set('email', 'owner@example.com')
            ->set('asset_type', 'Building wall')
            ->set('location', 'Petaling Jaya')
            ->set('description', 'A large wall facing a busy road with good visibility.')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('media_properties', ['status' => 'prospect', 'property_type' => 'prospect']);
        $this->assertDatabaseHas('media_assets', ['status' => 'prospect']);
        $this->assertDatabaseHas('enquiries', ['source' => 'asset_submission']);
    }
}
