<?php

namespace Tests\Feature;

use AIArmada\Cart\Models\CartModel;
use AIArmada\Products\Models\Product;
use App\Features\Advisor\EntryPoints\Livewire\MediaAdvisor;
use App\Features\Advisor\Models\AdvisorSession;
use App\Features\Identity\Models\User;
use App\Features\Sales\Models\Enquiry;
use App\Host\Ai\Agents\MediaStrategyAgent;
use App\Shared\Contracts\AdvisesMediaStrategy;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use RuntimeException;
use Tests\Concerns\CreatesMediaOffers;
use Tests\TestCase;

class MediaAdvisorTest extends TestCase
{
    use CreatesMediaOffers;
    use RefreshDatabase;

    public function test_the_workbench_route_and_unknown_permalink_behaviour(): void
    {
        $this->get('/media-advisor')->assertOk()->assertSee('Media advisor');
        $this->get('/media-advisor/ADV-NOT-FOUND')->assertNotFound();
    }

    public function test_a_generated_session_loads_through_its_permalink(): void
    {
        $this->seedCatalogue();

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();

        $this->assertStringStartsWith('ADV-', (string) $session->share_token);

        $this->get('/media-advisor/'.$session->share_token)
            ->assertOk()
            ->assertSee((string) $session->share_token);
    }

    public function test_the_brief_requires_a_valid_budget(): void
    {
        Livewire::test(MediaAdvisor::class)
            ->set('budgetAmount', 'not-a-number')
            ->call('generate')
            ->assertHasErrors('budgetAmount');

        $this->assertDatabaseCount('advisor_sessions', 0);
    }

    public function test_scoring_excludes_offers_that_blow_the_budget(): void
    {
        [$cheapRadio, $expensive] = $this->seedCatalogue();

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->set('regions', ['Klang Valley'])
            ->call('generate')
            ->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();
        $scenarios = $session->recommendations['scenarios'];

        foreach ($scenarios as $scenario) {
            $ids = array_column($scenario['lines'], 'product_id');

            $this->assertNotContains($expensive->id, $ids, 'The over-budget offer should never be recommended.');
        }

        $this->assertContains($cheapRadio->id, array_column($scenarios['lean']['lines'], 'product_id'));
    }

    public function test_scenario_switching_persists_the_shortlist(): void
    {
        $this->seedCatalogue();

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors()
            ->call('selectScenario', 'lean')
            ->assertSet('selectedScenario', 'lean');

        $session = AdvisorSession::query()->firstOrFail();
        $leanIds = array_column($session->recommendations['scenarios']['lean']['lines'], 'product_id');

        $this->assertSame($leanIds, $session->customized_product_ids);
    }

    public function test_a_buyer_can_add_an_offering_external_to_the_scenario(): void
    {
        $this->seedCatalogue(6);

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();
        $shortlist = $session->customized_product_ids ?? [];

        $extra = Product::query()->whereNotIn('id', $shortlist)->firstOrFail();

        Livewire::test(MediaAdvisor::class, ['token' => $session->share_token])
            ->call('addProduct', $extra->id)
            ->assertHasNoErrors()
            ->assertSee($extra->name)
            ->assertSee('Added from catalogue');

        $this->assertContains($extra->id, $session->fresh()->customized_product_ids);
    }

    public function test_unconfigured_api_key_keeps_ai_strategist_unavailable(): void
    {
        config()->set('duniamedia.advisor.ai_enabled', true);
        config()->set('duniamedia.advisor.ai_provider', null);
        config()->set('ai.default', 'openai');
        config()->set('ai.providers.openai.key', null);

        $strategist = app(AdvisesMediaStrategy::class);

        $this->assertFalse($strategist->isAvailable());
    }

    public function test_empty_shortlist_returns_validation_error_on_plan_and_proposal_actions(): void
    {
        $this->seedCatalogue();

        $component = Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->set('shortlist', []);

        $component->call('saveAsPlan')->assertHasErrors('shortlist');
        $component->call('continueToProposal')->assertHasErrors('shortlist');

        $this->assertDatabaseCount('carts', 0);
    }

    public function test_ai_assisted_synthesis_drops_hallucinated_products(): void
    {
        [$cheapRadio] = $this->seedCatalogue();

        MediaStrategyAgent::fake([[
            'headline' => 'AI headline',
            'diagnosis' => 'AI diagnosis',
            'scenarios' => [[
                'key' => 'balanced',
                'name' => 'AI Connected Mix',
                'headline' => 'AI balanced headline',
                'messaging' => 'AI messaging',
                'creative_angle' => 'AI creative angle',
                'diagnosis' => 'AI scenario diagnosis',
                'product_ids' => [$cheapRadio->id, 'ghost-product-id'],
            ]],
            'questions' => ['AI question one'],
        ]]);

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();

        $this->assertSame('ai_assisted', $session->generation_mode);
        $this->assertSame('AI headline', $session->recommendations['headline']);
        $this->assertSame(['AI question one'], $session->recommendations['questions']);
        $this->assertSame([$cheapRadio->id], $session->recommendations['scenarios']['balanced']['ai_product_ids']);
    }

    public function test_a_failing_provider_falls_back_to_the_deterministic_engine(): void
    {
        $this->seedCatalogue();

        MediaStrategyAgent::fake(function (): never {
            throw new RuntimeException('provider unavailable');
        });

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();

        $this->assertSame('catalogue_engine', $session->generation_mode);
        $this->assertNotEmpty($session->recommendations['scenarios']['balanced']['lines']);
    }

    public function test_follow_up_questions_are_answered_and_recorded(): void
    {
        $this->seedCatalogue();

        $component = Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors();

        $before = AdvisorSession::query()->firstOrFail()->messages()->count();

        $component->set('followUp', 'Can we reduce the budget?')->call('refine')->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();

        $this->assertSame($before + 2, $session->messages()->count());
        $this->assertSame('assistant', $session->messages()->latest('created_at')->first()->role);
        $component->assertSee('lean scenario');
    }

    public function test_the_shortlist_can_be_saved_and_continued_as_a_campaign_plan(): void
    {
        $this->seedCatalogue();

        $component = Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors()
            ->call('saveAsPlan')
            ->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();

        $this->assertSame('planned', $session->status);
        $this->assertNotNull($session->saved_plan_instance);
        $this->assertSame(1, CartModel::query()->count());

        $plan = CartModel::query()->firstOrFail();
        $this->assertSame($session->share_token, $plan->getMetadataValue('advisor_reference'));

        $component->call('continueToProposal')->assertRedirectContains('/quote-builder');
    }

    public function test_a_senior_planner_review_is_submitted_with_structured_metadata(): void
    {
        $this->seedCatalogue();

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->set('requesterName', 'Nadia Planner')
            ->set('requesterEmail', 'nadia@example.com')
            ->set('requesterCompany', 'Acme Sdn Bhd')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();

        $this->assertSame('submitted', $session->status);
        $this->assertNotNull($session->enquiry_id);

        $this->assertDatabaseHas('enquiries', [
            'source' => 'media_advisor',
            'budget_amount' => 20000,
            'currency_code' => 'MYR',
        ]);

        $metadata = Enquiry::query()->firstOrFail()->metadata;

        $this->assertIsArray($metadata);
        $this->assertSame($session->share_token, $metadata['advisor_reference']);
        $this->assertArrayHasKey('shortlist', $metadata);
        $this->assertArrayHasKey('services', $metadata);
    }

    public function test_submitting_requires_consent(): void
    {
        $this->seedCatalogue();

        Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->set('requesterName', 'No Consent')
            ->set('requesterEmail', 'noconsent@example.com')
            ->call('submit')
            ->assertHasErrors('consent');
    }

    public function test_filament_advisor_sessions_are_visible_to_admins(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $admin->assignRole('super_admin');

        $this->actingAs($admin);

        $this->get('/admin/advisor-sessions')->assertOk();
    }

    public function test_filament_advisor_sessions_are_hidden_from_editors(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $editor = User::query()->create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => 'password',
        ]);
        $editor->assignRole('editor');

        $this->actingAs($editor);

        $this->get('/admin/advisor-sessions')->assertForbidden();
    }

    public function test_the_basket_lists_every_selection_removed_from_any_scenario_tab(): void
    {
        $this->seedCatalogue();

        $component = Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();
        $leanIds = array_column($session->recommendations['scenarios']['lean']['lines'], 'product_id');

        $component->call('selectScenario', 'lean');

        $this->assertNotEmpty($leanIds, 'The lean scenario must recommend something to basket.');

        $component
            ->assertSee('Your basket')
            ->assertSee('Clear all');

        $removed = $leanIds[0];

        $component->call('removeFromBasket', $removed)->assertHasNoErrors();

        $this->assertNotContains($removed, $session->fresh()->customized_product_ids);
        $component->assertDontSee('Remove '.$removed.' from basket');
    }

    public function test_the_basket_shows_an_empty_state_once_everything_is_cleared(): void
    {
        $this->seedCatalogue();

        $component = Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors()
            ->assertSee('Your basket');

        $this->assertNotEmpty(AdvisorSession::query()->firstOrFail()->customized_product_ids);

        $component->call('clearBasket')->assertHasNoErrors()->assertSee('Nothing added yet');

        $this->assertSame([], AdvisorSession::query()->firstOrFail()->customized_product_ids);
    }

    public function test_the_basket_lists_items_added_from_another_scenario(): void
    {
        $this->seedCatalogue(6);

        $component = Livewire::test(MediaAdvisor::class)
            ->set('objective', 'awareness')
            ->set('budgetAmount', '20000')
            ->call('generate')
            ->assertHasNoErrors();

        $session = AdvisorSession::query()->firstOrFail();
        $leanIds = array_column($session->recommendations['scenarios']['lean']['lines'], 'product_id');
        $impactIds = array_column($session->recommendations['scenarios']['impact']['lines'], 'product_id');

        $impactOnly = array_values(array_diff($impactIds, $leanIds));

        $component->call('selectScenario', 'lean');

        if ($impactOnly === []) {
            $this->assertNotEmpty($leanIds);
            $component->call('addProduct', $leanIds[0])->assertHasNoErrors();
            $component->assertSee('Added from catalogue');

            return;
        }

        $component->call('addProduct', $impactOnly[0])->assertHasNoErrors();

        $this->assertContains($impactOnly[0], $session->fresh()->customized_product_ids);
    }

    /**
     * @return array{0: Product, 1: Product}
     */
    private function seedCatalogue(int $cheapCount = 4): array
    {
        $mediums = ['Radio', 'Outdoor', 'Digital', 'Print', 'TV & Broadcast', 'Creators', 'Indoor'];
        $names = ['Alpha Offer', 'Beta Offer', 'Gamma Offer', 'Delta Offer', 'Epsilon Offer', 'Zeta Offer', 'Eta Offer'];
        $cheap = [];

        for ($index = 0; $index < $cheapCount; $index++) {
            $cheap[] = $this->createMediaOffer([
                'name' => $names[$index],
                'slug' => 'advisor-'.strtolower(str_replace(' ', '-', $names[$index])),
            ], [
                'medium' => $mediums[$index % count($mediums)],
                'location_label' => 'Klang Valley',
                'price_from' => 5000,
                'pricing_mode' => 'fixed',
            ]);
        }

        $expensive = $this->createMediaOffer([
            'name' => 'Mega Flagship',
            'slug' => 'advisor-mega-flagship',
        ], [
            'medium' => 'Outdoor',
            'location_label' => 'Klang Valley',
            'price_from' => 500000,
            'pricing_mode' => 'fixed',
        ]);

        return [$cheap[0], $expensive];
    }
}
