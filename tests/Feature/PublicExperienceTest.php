<?php

namespace Tests\Feature;

use App\Livewire\CampaignPlanner;
use App\Livewire\ContactForm;
use App\Livewire\MediaExplorer;
use App\Livewire\QuoteBuilder;
use App\Models\MediaListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublicExperienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_routes_render_the_redesigned_experience(): void
    {
        $media = MediaListing::create([
            'title' => 'Test Billboard',
            'slug' => 'test-billboard',
            'type' => 'Outdoor',
            'location' => 'Klang Valley',
            'summary' => 'A test outdoor opportunity.',
            'is_published' => true,
        ]);

        foreach ([
            '/',
            '/media',
            '/media/test-billboard',
            '/media/owners/media-prima',
            '/media/owners/media-prima/tv3',
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

        $this->get('/media/test-billboard')->assertSee($media->title);
    }

    public function test_media_explorer_filters_and_clears_from_url_state(): void
    {
        MediaListing::create(['title' => 'Radio One', 'slug' => 'radio-one', 'type' => 'Radio', 'summary' => 'Radio', 'is_published' => true]);
        MediaListing::create(['title' => 'Outdoor One', 'slug' => 'outdoor-one', 'type' => 'Outdoor', 'summary' => 'Outdoor', 'is_published' => true]);

        Livewire::test(MediaExplorer::class)
            ->set('type', 'Radio')
            ->assertSee('Radio One')
            ->assertDontSee('Outdoor One')
            ->call('clearFilters')
            ->assertSet('type', 'all')
            ->assertSet('vendor', 'all')
            ->assertSet('budget', 'any');
    }

    public function test_quote_builder_creates_a_request_not_a_booking(): void
    {
        $media = MediaListing::create([
            'title' => 'Test Radio Spot',
            'slug' => 'test-radio-spot',
            'type' => 'Radio',
            'summary' => 'A test radio spot.',
            'pricing_mode' => 'on_request',
            'is_published' => true,
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
        $this->assertDatabaseCount('leads', 1);
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

        $this->assertDatabaseHas('leads', ['source' => 'media_advisor', 'email' => 'planner@example.com']);
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

        $this->assertDatabaseHas('leads', ['source' => 'contact', 'email' => 'contact@example.com']);
    }
}
