<?php

namespace Tests\Feature;

use App\Features\Advisor\EntryPoints\Livewire\MediaAdvisor;
use App\Features\Catalog\EntryPoints\Livewire\MediaExplorer;
use App\Features\Creators\EntryPoints\Livewire\CreatorExplorer;
use App\Features\Creators\EntryPoints\Livewire\CreatorJoin;
use App\Features\Creators\Models\CreatorProfile;
use App\Features\Identity\Models\Party;
use App\Features\Sales\EntryPoints\Livewire\AssetOwnerForm;
use App\Features\Sales\EntryPoints\Livewire\ContactForm;
use App\Features\Sales\EntryPoints\Livewire\QuoteBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\CreatesMediaOffers;
use Tests\TestCase;

class PublicExperienceTest extends TestCase
{
    use CreatesMediaOffers;
    use RefreshDatabase;

    public function test_public_routes_render_the_redesigned_experience(): void
    {
        $media = $this->createMediaOffer([
            'name' => 'Test Billboard',
            'slug' => 'test-billboard',
            'short_description' => 'A test outdoor opportunity.',
        ], [
            'medium' => 'Outdoor',
            'location_label' => 'Klang Valley',
        ]);

        foreach ([
            '/',
            '/media',
            '/offers/test-billboard',
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

        $this->get('/offers/test-billboard')->assertSee($media->name);
    }

    public function test_work_and_about_use_genuine_archive_film_stills(): void
    {
        $this->get(route('work.index'))
            ->assertOk()
            ->assertSee('work-film-feature')
            ->assertSee('id="archive"', false);
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('assets/work/covers/jwp-psa-kl.webp')
            ->assertSee('assets/work/covers/st-nlko-2025-highlight.webp');
    }

    public function test_services_editorial_page_preserves_all_capabilities_and_real_work(): void
    {
        $this->seed(\Database\Seeders\ServiceSeeder::class);

        $response = $this->get(route('services'))
            ->assertOk()
            ->assertSee('Good ideas.')
            ->assertSee('Real impact.')
            ->assertSee('Media Planning & Buying')
            ->assertSee('Branding & Creative Studio')
            ->assertSee('AI Video & Image Production')
            ->assertSee('Websites & Conversion Systems')
            ->assertSee('Creator & Influencer Campaigns')
            ->assertSee('Media Asset Monetisation')
            ->assertSee('AI Seminars & Corporate Training')
            ->assertSee('Campaign Analytics & Automation')
            ->assertSee('Typical deliverables')
            ->assertSee(route('advisor', ['objective' => 'launch']))
            ->assertSee(route('advisor', ['objective' => 'leads']))
            ->assertSee(route('assets.join'))
            ->assertSee(route('academy'))
            ->assertSee('assets/work/covers/jwp-psa-kl.webp')
            ->assertSee('#work-media-kpj-2025-10-02', false);

        foreach (['planning', 'creative', 'ai-production', 'technology', 'creators', 'placements', 'training', 'measurement'] as $anchor) {
            $this->assertSame(1, substr_count($response->getContent(), 'id="'.$anchor.'"'));
        }

        $this->assertSame(8, substr_count($response->getContent(), '<details class="services-detail">'));
    }

    public function test_solution_objectives_link_to_matching_strategy_briefs(): void
    {
        $this->get(route('solutions'))
            ->assertOk()
            ->assertSee(route('advisor', ['objective' => 'leads']))
            ->assertSee(route('advisor', ['objective' => 'launch']));
    }

    public function test_homepage_connects_media_discovery_to_campaign_workspaces(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Discover, Compare')
            ->assertSee('Media Rate Exchange')
            ->assertSee('Proposal Studio')
            ->assertSee('4.3 million')
            ->assertSee('Source: Media Prima Audio')
            ->assertSee('assets/home/hot-fm-activation.webp')
            ->assertSee('assets/home/tv3-newsroom.webp')
            ->assertSee('assets/home/big-tree-klcc.webp')
            ->assertSee('assets/home/sinar-editorial.webp')
            ->assertSee('assets/work/covers/jwp-psa-kl.webp')
            ->assertSee('assets/work/covers/kpj-2025-10-02-highlight.webp')
            ->assertSee('#work-media-jwp-psa-kl', false)
            ->assertSee('action="'.route('search').'"', false)
            ->assertSee(route('media.channel', ['media-prima', 'hot-fm']))
            ->assertSee(route('media.owner', 'astro'))
            ->assertSee(route('advisor'))
            ->assertSee(route('plans.index'))
            ->assertSee(route('quote.builder'))
            ->assertDontSee('hero-city-mobile-bg-640.webp');

        $this->get('/search?q=Bekpes')->assertOk()->assertSee('Bekpes Hot')
            ->assertSee('#hotfm-programme-bekpes-hot', false);
        $this->get('/search?q=Media+Prima')->assertOk()->assertSee('Media Prima Berhad')
            ->assertSee(route('media.owner', 'media-prima'));
        $this->get('/search?q=Keymi')->assertOk()->assertSee('FYP@Hot');
    }

    public function test_work_page_exposes_every_playable_archive_asset(): void
    {
        $response = $this->get('/work');

        $response->assertOk()
            ->assertSee('Work that speaks for')
            ->assertSee('work-next-campaign-mobile.jpg');

        foreach (\App\Features\Clients\Support\ClientWork::media() as $item) {
            $response->assertSee('id="work-media-'.$item['key'].'"', false)
                ->assertSee(route('work.media', $item['key']));
        }

        $this->assertFileExists(public_path('assets/img/work-next-campaign.jpg'));
        $this->assertFileExists(public_path('assets/img/work-next-campaign-mobile.jpg'));
    }

    public function test_about_page_keeps_the_responsive_story_and_client_roster_available(): void
    {
        $response = $this->get('/about');

        $response->assertOk()
            ->assertSee('name="viewport"', false)
            ->assertSee('about-editorial-kl-mobile.jpg')
            ->assertSee('760w')
            ->assertSee('about-editorial-kl.jpg')
            ->assertSee('1672w')
            ->assertSee('class="about-hero__break"', false)
            ->assertSee('class="about-future__actions"', false)
            ->assertSee('class="about-client-disclosure"', false)
            ->assertSee('45 organisations');

        $groups = require resource_path('data/about-clients.php');

        foreach ($groups as $group) {
            foreach ($group['clients'] as $client) {
                $response->assertSee($client['name']);
            }
        }

        $this->assertFileExists(public_path('assets/img/about-editorial-kl-mobile.jpg'));
        $this->assertFileExists(public_path('assets/img/about-editorial-kl.jpg'));
    }

    public function test_global_search_finds_media_and_creators(): void
    {
        $this->createMediaOffer([
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
        $this->createMediaOffer(['name' => 'Radio One', 'slug' => 'radio-one'], ['medium' => 'Radio']);
        $this->createMediaOffer(['name' => 'Outdoor One', 'slug' => 'outdoor-one'], ['medium' => 'Outdoor']);

        Livewire::test(MediaExplorer::class)
            ->set('medium', 'Radio')
            ->assertSee('Radio One')
            ->assertDontSee('Outdoor One')
            ->call('clearFilters')
            ->assertSet('medium', 'all')
            ->assertSet('channel', 'all')
            ->assertSet('budget', 'any');
    }

    public function test_media_explorer_filters_by_platform_and_media_owner(): void
    {
        $owner = Party::factory()->create([
            'slug' => 'test-media-owner',
            'display_name' => 'Test Media Owner',
        ]);

        $this->createMediaOffer([
            'name' => 'Owner Billboard',
            'slug' => 'owner-billboard',
        ], [
            'medium' => 'Outdoor',
            'media_owner_party_id' => $owner->id,
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

        $media = $this->createMediaOffer([
            'name' => 'Test Radio Spot',
            'slug' => 'test-radio-spot',
        ], [
            'medium' => 'Radio',
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

    public function test_advisor_generates_a_catalogue_grounded_strategy(): void
    {
        $this->createMediaOffer([
            'name' => 'Advisor Radio Spot',
            'slug' => 'advisor-radio-spot',
        ], [
            'medium' => 'Radio',
            'location_label' => 'Klang Valley',
            'price_from' => 12000,
            'pricing_mode' => 'fixed',
        ]);

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'leads')
            ->set('budgetAmount', '50000')
            ->set('regions', ['Klang Valley'])
            ->set('languages', ['Malay', 'English'])
            ->call('generate')
            ->assertHasNoErrors()
            ->assertSet('generated', true)
            ->assertSee('Advisor Radio Spot');

        $this->assertDatabaseCount('advisor_sessions', 1);
        $this->assertDatabaseCount('advisor_messages', 1);
    }

    public function test_advisor_submits_a_brief_for_senior_planner_review(): void
    {
        $this->createMediaOffer([
            'name' => 'Advisor Billboard',
            'slug' => 'advisor-billboard',
        ], [
            'medium' => 'Outdoor',
            'location_label' => 'Klang Valley',
        ]);

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '80000')
            ->call('generate')
            ->set('requesterName', 'Test Planner')
            ->set('requesterEmail', 'planner@example.com')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('enquiries', ['source' => 'media_advisor']);
    }

    public function test_contact_form_saves_a_consented_enquiry(): void
    {
        $this->configureContactTurnstile();
        Http::preventStrayRequests();
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
                'success' => true,
                'action' => 'contact',
                'hostname' => 'duniamedia.test',
            ]),
        ]);

        Livewire::test(ContactForm::class)
            ->set('name', 'Test Contact')
            ->set('email', 'contact@example.com')
            ->set('message', 'We would like to discuss a campaign for a new product launch.')
            ->set('consent', true)
            ->set('turnstileToken', 'verified-contact-token')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true)
            ->assertSet('turnstileToken', '');

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://challenges.cloudflare.com/turnstile/v0/siteverify'
            && $request['secret'] === 'test-secret'
            && $request['response'] === 'verified-contact-token');
        $this->assertDatabaseHas('enquiries', ['source' => 'contact']);
    }

    public function test_contact_form_rejects_a_missing_turnstile_token(): void
    {
        $this->configureContactTurnstile();
        Http::preventStrayRequests();
        Http::fake(['https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response()]);

        Livewire::test(ContactForm::class)
            ->set('name', 'Test Contact')
            ->set('email', 'contact@example.com')
            ->set('message', 'We would like to discuss a campaign.')
            ->set('consent', true)
            ->call('submit')
            ->assertHasErrors(['turnstileToken' => 'required'])
            ->assertSet('submitted', false);

        Http::assertNothingSent();
        $this->assertDatabaseCount('enquiries', 0);
        $this->assertDatabaseMissing('parties', ['display_name' => 'Test Contact']);
    }

    #[DataProvider('rejectedTurnstileResponses')]
    public function test_contact_form_rejects_unsuccessful_turnstile_verification(array $payload, int $status): void
    {
        $this->configureContactTurnstile();
        Http::preventStrayRequests();
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response($payload, $status),
        ]);

        Livewire::test(ContactForm::class)
            ->set('name', 'Test Contact')
            ->set('email', 'contact@example.com')
            ->set('message', 'We would like to discuss a campaign.')
            ->set('consent', true)
            ->set('turnstileToken', 'unverified-token')
            ->call('submit')
            ->assertHasErrors(['turnstileToken'])
            ->assertDispatched('contact-turnstile-reset')
            ->assertSet('submitted', false)
            ->assertSet('turnstileToken', '');

        Http::assertSentCount(1);
        $this->assertDatabaseCount('enquiries', 0);
        $this->assertDatabaseMissing('parties', ['display_name' => 'Test Contact']);
    }

    public static function rejectedTurnstileResponses(): array
    {
        return [
            'invalid token' => [['success' => false, 'error-codes' => ['invalid-input-response']], 200],
            'expired or replayed token' => [['success' => false, 'error-codes' => ['timeout-or-duplicate']], 200],
            'wrong action' => [['success' => true, 'action' => 'other', 'hostname' => 'duniamedia.test'], 200],
            'wrong hostname' => [['success' => true, 'action' => 'contact', 'hostname' => 'other.test'], 200],
            'upstream outage' => [[], 503],
        ];
    }

    public function test_contact_form_fails_closed_without_turnstile_credentials(): void
    {
        config(['turnstile.site_key' => null, 'turnstile.secret_key' => null]);
        Http::preventStrayRequests();
        Http::fake(['https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response()]);

        Livewire::test(ContactForm::class)
            ->set('name', 'Test Contact')
            ->set('email', 'contact@example.com')
            ->set('message', 'We would like to discuss a campaign.')
            ->set('consent', true)
            ->set('turnstileToken', 'forged-token')
            ->call('submit')
            ->assertHasErrors(['turnstileToken'])
            ->assertSet('submitted', false);

        Http::assertNothingSent();
        $this->assertDatabaseCount('enquiries', 0);
    }

    public function test_contact_form_fails_closed_without_an_approved_turnstile_hostname(): void
    {
        $this->configureContactTurnstile();
        config(['turnstile.hostname' => '']);
        Http::preventStrayRequests();
        Http::fake(['https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response()]);

        Livewire::test(ContactForm::class)
            ->set('name', 'Test Contact')
            ->set('email', 'contact@example.com')
            ->set('message', 'We would like to discuss a campaign.')
            ->set('consent', true)
            ->set('turnstileToken', 'unverified-token')
            ->call('submit')
            ->assertHasErrors(['turnstileToken'])
            ->assertSet('submitted', false);

        Http::assertNothingSent();
        $this->assertDatabaseCount('enquiries', 0);
        $this->assertDatabaseMissing('parties', ['display_name' => 'Test Contact']);
    }

    public function test_contact_form_fails_closed_when_turnstile_cannot_be_reached(): void
    {
        $this->configureContactTurnstile();
        Http::preventStrayRequests();
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::failedConnection(),
        ]);

        Livewire::test(ContactForm::class)
            ->set('name', 'Test Contact')
            ->set('email', 'contact@example.com')
            ->set('message', 'We would like to discuss a campaign.')
            ->set('consent', true)
            ->set('turnstileToken', 'unverified-token')
            ->call('submit')
            ->assertHasErrors(['turnstileToken'])
            ->assertDispatched('contact-turnstile-reset')
            ->assertSet('submitted', false);

        $this->assertDatabaseCount('enquiries', 0);
        $this->assertDatabaseMissing('parties', ['display_name' => 'Test Contact']);
    }

    private function configureContactTurnstile(): void
    {
        config([
            'turnstile.site_key' => 'test-site-key',
            'turnstile.secret_key' => 'test-secret',
            'turnstile.hostname' => 'duniamedia.test',
        ]);
    }

    public function test_creator_profile_has_public_media_kit_and_creator_specific_enquiry(): void
    {
        $party = Party::factory()->create(['display_name' => 'Profile Creator']);
        $profile = CreatorProfile::factory()->create([
            'creator_party_id' => $party->id,
            'slug' => 'profile-creator',
            'social_platform' => 'TikTok',
            'handle' => '@profile_creator',
        ]);

        $this->get(route('creators.index'))
            ->assertOk()
            ->assertSee(route('creators.show', $profile->slug));

        $this->get(route('creators.show', $profile->slug))
            ->assertOk()
            ->assertSee('Profile Creator')
            ->assertSee('@profile_creator')
            ->assertSee('https://www.tiktok.com/@profile_creator')
            ->assertSee('Enquire about current audience insights.')
            ->assertSee('Content focus')
            ->assertSee(route('contact', ['creator' => $profile->slug]).'#enquiry')
            ->assertDontSee('Metrics as of');

        $this->get(route('search', ['q' => '@profile_creator']))
            ->assertOk()
            ->assertSee(route('creators.show', $profile->slug));

        $this->get(route('contact', ['creator' => $profile->slug]))
            ->assertOk()
            ->assertSee('I would like to discuss a collaboration with Profile Creator.');
    }

    public function test_creator_profile_rejects_unverified_paused_and_demo_profiles(): void
    {
        foreach ([
            ['slug' => 'pending-profile', 'verification_status' => 'pending'],
            ['slug' => 'paused-profile', 'status' => 'paused'],
            ['slug' => 'demo-creator-999'],
        ] as $attributes) {
            $profile = CreatorProfile::factory()->create($attributes);

            $this->get(route('creators.show', $profile->slug))->assertNotFound();
        }

        $this->get(route('creators.show', 'nonexistent-creator'))->assertNotFound();
        $this->get(route('creators.join'))->assertOk();
    }

    public function test_drqis_seed_is_idempotent_and_preserves_unverified_status(): void
    {
        $this->seed(\Database\Seeders\DrqisCreatorSeeder::class);
        $this->seed(\Database\Seeders\DrqisCreatorSeeder::class);

        $this->assertDatabaseCount('creator_profiles', 1);
        $this->assertDatabaseCount('creator_accounts', 1);
        $this->assertDatabaseHas('creator_profiles', [
            'slug' => 'drqis',
            'handle' => '@drqis',
            'status' => 'pending',
            'verification_status' => 'pending',
        ]);
        $this->assertDatabaseHas('creator_accounts', [
            'handle' => '@drqis',
            'followers' => null,
            'average_views' => null,
            'engagement_rate' => null,
            'metrics_as_of' => null,
        ]);
        $this->get(route('creators.show', 'drqis'))->assertNotFound();

        $profile = CreatorProfile::query()->where('slug', 'drqis')->firstOrFail();
        $this->assertCount(1, $profile->getMedia('image'));
        $this->assertCount(2, $profile->getMedia('gallery'));

        app(\App\Features\Creators\Persistence\WriteCreatorProfiles::class)->update($profile, [
            'status' => 'active',
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        $this->get(route('creators.show', 'drqis'))
            ->assertOk()
            ->assertSee('Dr Qistina')
            ->assertSee('Oral health education')
            ->assertSee('Dental care')
            ->assertSee('assets/creators/drqis/portrait-480.webp')
            ->assertSee('assets/creators/drqis/portrait-02-480.webp')
            ->assertSee('assets/creators/drqis/portrait-03-480.webp')
            ->assertSee('assets/creators/drqis/dental-still-life.webp')
            ->assertSee('https://www.tiktok.com/@drqis')
            ->assertDontSee('Metrics as of');

        $this->get(route('creators.index'))->assertOk()->assertSee(route('creators.show', 'drqis'));
    }

    public function test_creator_directory_keeps_filters_available_when_no_creators_match(): void
    {
        $party = Party::factory()->create(['display_name' => 'Verified Travel Creator']);
        CreatorProfile::factory()->create([
            'creator_party_id' => $party->id,
            'primary_niche' => 'Travel',
            'social_platform' => 'TikTok',
        ]);

        Livewire::test(CreatorExplorer::class)
            ->set('niche', 'Food')
            ->assertSee('No creators match your search.')
            ->assertSee('Clear filters')
            ->assertDontSee('Verified Travel Creator')
            ->call('clearFilters')
            ->assertSet('niche', 'all')
            ->assertSee('Verified Travel Creator')
            ->set('search', 'TikTok')
            ->assertSee('Verified Travel Creator')
            ->set('search', 'Travel')
            ->assertSee('Verified Travel Creator');
    }

    public function test_creator_directory_excludes_seeded_demo_and_unverified_profiles(): void
    {
        $demo = Party::factory()->create(['display_name' => 'Seeded Demo Creator']);
        CreatorProfile::factory()->create([
            'creator_party_id' => $demo->id,
            'slug' => 'demo-creator-1',
        ]);
        $pending = Party::factory()->create(['display_name' => 'Pending Creator']);
        CreatorProfile::factory()->create([
            'creator_party_id' => $pending->id,
            'verification_status' => 'pending',
        ]);

        $this->get('/creators')
            ->assertOk()
            ->assertSee('Our creator network is growing.')
            ->assertDontSee('Seeded Demo Creator')
            ->assertDontSee('Pending Creator');
    }

    public function test_creator_application_creates_a_pending_creator_profile(): void
    {
        Livewire::test(CreatorJoin::class)
            ->set('name', 'New Creator')
            ->set('email', 'creator@example.com')
            ->set('handle', '@newcreator')
            ->set('socialPlatform', 'TikTok')
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

    public function test_media_owners_can_be_filtered_by_category_and_channel_name(): void
    {
        $this->get('/media/owners?category=transit')
            ->assertOk()
            ->assertSee('KTM Advertising Space')
            ->assertSee('KLIA')
            ->assertDontSee(route('media.owner', 'media-prima'), false);

        $this->get('/media/owners?category=television&q=TV3')
            ->assertOk()
            ->assertSee('Media Prima Berhad')
            ->assertDontSee(route('media.owner', 'ktm'), false);

        $this->get('/media/owners?category=transit&q=TV3')
            ->assertOk()
            ->assertSee('No media owners match your search.');
    }
}
