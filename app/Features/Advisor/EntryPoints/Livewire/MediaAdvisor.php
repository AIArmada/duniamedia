<?php

declare(strict_types=1);

namespace App\Features\Advisor\EntryPoints\Livewire;

use App\Features\Advisor\Actions\ConvertAdvisorToPlan;
use App\Features\Advisor\Actions\GenerateAdvisorStrategy;
use App\Features\Advisor\Actions\RefineAdvisorSession;
use App\Features\Advisor\Actions\SubmitAdvisorBrief;
use App\Features\Advisor\Actions\UpdateAdvisorShortlist;
use App\Features\Advisor\Domain\AdvisorBriefInput;
use App\Features\Advisor\Domain\AdvisorPresets;
use App\Features\Advisor\Models\AdvisorMessage;
use App\Features\Advisor\Models\AdvisorSession;
use App\Features\Catalog\Api\MediaOfferView;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Clients\Api\ListsServices;
use Livewire\Component;

class MediaAdvisor extends Component
{
    public ?string $token = null;

    public ?string $sessionId = null;

    public bool $generated = false;

    public string $workspaceTab = 'brief';

    public string $generationMode = 'catalogue_engine';

    public string $executiveHeadline = '';

    public string $executiveDiagnosis = '';

    public string $activePreset = '';

    public string $addOfferingId = '';

    /** @var list<array<string, mixed>> */
    public array $presets = [];

    public string $objective = 'awareness';

    public string $budgetAmount = '50000';

    public string $budgetFlexibility = 'guided';

    /** @var list<string> */
    public array $regions = ['Klang Valley'];

    /** @var list<string> */
    public array $languages = ['Malay', 'English'];

    public string $industry = '';

    public string $audience = '';

    public string $timeline = 'one_to_three_months';

    public string $launchWindow = '';

    /** @var list<string> */
    public array $preferredMediums = [];

    public string $creativeReadiness = 'ready';

    public string $brandContext = '';

    /** @var array<string, array<string, mixed>> */
    public array $scenarios = [];

    public string $selectedScenario = 'balanced';

    /** @var list<string> */
    public array $shortlist = [];

    /** @var list<string> */
    public array $selectedServices = [];

    /** @var array<string, string> */
    public array $catalogueOptions = [];

    /** @var list<array{role: string, content: string, suggestions: list<string>}> */
    public array $conversation = [];

    public string $followUp = '';

    public string $requesterName = '';

    public string $requesterEmail = '';

    public string $requesterCompany = '';

    public string $requesterPhone = '';

    public string $website = '';

    public bool $consent = false;

    public bool $submitted = false;

    public ?string $planInstance = null;

    /** @var list<array{token: string, objective: string, generated_at: ?string}> */
    public array $recent = [];

    public function mount(?string $token = null): void
    {
        $this->presets = AdvisorPresets::all();
        $this->catalogueOptions = app(PresentsMedia::class)->optionList();
        $this->recent = $this->recentConsultations();

        if ($token === null) {
            return;
        }

        $session = AdvisorSession::query()->where('share_token', $token)->first();

        abort_if($session === null, 404);

        $this->token = (string) $session->share_token;
        $this->sessionId = $session->id;
        $this->fillFromSession($session);
    }

    public function applyPreset(string $key): void
    {
        foreach ($this->presets as $preset) {
            if (($preset['key'] ?? null) !== $key) {
                continue;
            }

            $this->activePreset = $key;
            $this->objective = (string) $preset['objective'];
            $this->budgetAmount = (string) (int) $preset['budget_amount'];
            $this->budgetFlexibility = (string) $preset['budget_flexibility'];
            $this->regions = array_map('strval', (array) $preset['regions']);
            $this->languages = array_map('strval', (array) $preset['languages']);
            $this->timeline = (string) $preset['timeline'];
            $this->launchWindow = (string) ($preset['launch_window'] ?? '');
            $this->industry = (string) ($preset['industry'] ?? '');
            $this->audience = (string) ($preset['audience'] ?? '');
            $this->preferredMediums = array_map('strval', (array) $preset['preferred_mediums']);

            return;
        }
    }

    public function setBudgetPreset(int $amount): void
    {
        $this->budgetAmount = (string) $amount;
    }

    public function updatedAddOfferingId(string $value, UpdateAdvisorShortlist $action): void
    {
        if ($value === '') {
            return;
        }

        $this->addProduct($value, $action);
        $this->addOfferingId = '';
    }

    public function generate(GenerateAdvisorStrategy $action, UpdateAdvisorShortlist $shortlistAction): void
    {
        $this->validate([
            'budgetAmount' => 'required|numeric|min:1000',
            'objective' => 'required|string',
            'timeline' => 'required|string',
        ]);

        $session = $action->handle($this->brief(), $this->sessionIdentifier(), $this->currentSession());

        $this->token = (string) $session->share_token;
        $this->sessionId = $session->id;
        $this->applySession($session);
        $this->saveShortlist($shortlistAction);
        $this->rememberConsultation($session);
    }

    public function selectScenario(string $key, UpdateAdvisorShortlist $action): void
    {
        if (! isset($this->scenarios[$key])) {
            return;
        }

        $this->selectedScenario = $key;
        $this->shortlist = $this->scenarioProductIds($key);
        $this->saveShortlist($action);
    }

    public function toggleProduct(string $productId, UpdateAdvisorShortlist $action): void
    {
        if (in_array($productId, $this->shortlist, true)) {
            $this->shortlist = array_values(array_diff($this->shortlist, [$productId]));
        } else {
            $this->shortlist[] = $productId;
        }

        $this->saveShortlist($action);
    }

    public function removeFromBasket(string $productId, UpdateAdvisorShortlist $action): void
    {
        $this->shortlist = array_values(array_diff($this->shortlist, [$productId]));
        $this->resetErrorBag('shortlist');
        $this->saveShortlist($action);
    }

    public function removeService(string $slug, UpdateAdvisorShortlist $action): void
    {
        $this->selectedServices = array_values(array_diff($this->selectedServices, [$slug]));
        $this->saveShortlist($action);
    }

    public function clearBasket(UpdateAdvisorShortlist $action): void
    {
        $this->shortlist = [];
        $this->selectedServices = [];
        $this->resetErrorBag('shortlist');
        $this->saveShortlist($action);
    }

    public function addProduct(string $productId, UpdateAdvisorShortlist $action): void
    {
        if ($productId !== '' && ! in_array($productId, $this->shortlist, true)) {
            $this->shortlist[] = $productId;
        }

        $this->addOfferingId = '';
        $this->resetErrorBag('shortlist');
        $this->saveShortlist($action);
    }

    public function toggleService(string $slug, UpdateAdvisorShortlist $action): void
    {
        if (in_array($slug, $this->selectedServices, true)) {
            $this->selectedServices = array_values(array_diff($this->selectedServices, [$slug]));
        } else {
            $this->selectedServices[] = $slug;
        }

        $this->saveShortlist($action);
    }

    public function refine(RefineAdvisorSession $action): void
    {
        $question = trim($this->followUp);
        $session = $this->currentSession();

        if ($question === '' || $session === null) {
            return;
        }

        $this->followUp = '';
        $this->conversation = $this->conversationFrom($action->handle($session, $question));
    }

    public function quickRefine(string $prompt, RefineAdvisorSession $action): void
    {
        $this->followUp = $prompt;
        $this->refine($action);
    }

    public function saveAsPlan(ConvertAdvisorToPlan $action, UpdateAdvisorShortlist $shortlistAction): void
    {
        $session = $this->currentSession();

        if ($session === null) {
            return;
        }

        if ($this->shortlist === []) {
            $this->addError('shortlist', 'Select at least one media opportunity before saving a campaign plan.');

            return;
        }

        $this->resetErrorBag('shortlist');
        $this->saveShortlist($shortlistAction);
        $session = $this->currentSession();

        if ($session === null) {
            return;
        }

        $receipt = $action->handle($session);

        $this->planInstance = $receipt->instance;
        $this->token = (string) $session->fresh()?->share_token;
    }

    public function continueToProposal(ConvertAdvisorToPlan $action, UpdateAdvisorShortlist $shortlistAction)
    {
        $session = $this->currentSession();

        if ($session === null) {
            return null;
        }

        if ($this->shortlist === []) {
            $this->addError('shortlist', 'Select at least one media opportunity before continuing to the proposal builder.');

            return null;
        }

        $this->resetErrorBag('shortlist');
        $this->saveShortlist($shortlistAction);
        $session = $this->currentSession();

        if ($session === null) {
            return null;
        }

        $receipt = $action->handle($session);

        return $this->redirectRoute('quote.builder', ['plan' => $receipt->instance]);
    }

    public function submit(SubmitAdvisorBrief $action): void
    {
        $data = $this->validate([
            'requesterName' => 'required|string|max:120',
            'requesterEmail' => 'required|email|max:255',
            'requesterCompany' => 'nullable|string|max:160',
            'requesterPhone' => 'nullable|string|max:40',
            'consent' => 'accepted',
            'website' => 'max:0',
        ]);

        $session = $this->currentSession();

        if ($session === null) {
            return;
        }

        $action->handle($session, [
            'name' => $data['requesterName'],
            'email' => $data['requesterEmail'],
            'company' => $data['requesterCompany'] ?? null,
            'phone' => $data['requesterPhone'] ?? null,
        ], true);

        $this->submitted = true;
    }

    /**
     * Every selected offering, resolved across all scenarios so the basket stays
     * complete no matter which scenario tab is currently active.
     *
     * @return list<array<string, mixed>>
     */
    public function basketLines(): array
    {
        if ($this->shortlist === []) {
            return [];
        }

        $selected = [];

        foreach ($this->shortlist as $productId) {
            $selected[(string) $productId] = true;
        }

        $lines = [];
        $resolved = [];

        foreach ($this->scenarios as $scenario) {
            foreach (is_array($scenario['lines'] ?? null) ? $scenario['lines'] : [] as $line) {
                if (! is_array($line) || ! isset($line['product_id'])) {
                    continue;
                }

                $productId = (string) $line['product_id'];

                if (! isset($selected[$productId]) || isset($resolved[$productId])) {
                    continue;
                }

                $resolved[$productId] = true;
                $lines[] = $line;
            }
        }

        $missing = array_values(array_filter(
            $this->shortlist,
            static fn (string $productId): bool => ! isset($resolved[$productId]),
        ));

        foreach ($this->linesForIds($missing) as $line) {
            $productId = (string) ($line['product_id'] ?? '');

            if ($productId === '' || ! isset($selected[$productId]) || isset($resolved[$productId])) {
                continue;
            }

            $resolved[$productId] = true;
            $lines[] = $line;
        }

        return $lines;
    }

    /**
     * Names for the selected services, including ones not in the active scenario.
     *
     * @return list<array{slug: string, name: string}>
     */
    public function basketServices(): array
    {
        if ($this->selectedServices === []) {
            return [];
        }

        $options = app(ListsServices::class)->options();

        return array_map(
            static fn (string $slug): array => [
                'slug' => $slug,
                'name' => $options[$slug] ?? ucfirst(str_replace(['-', '_'], ' ', $slug)),
            ],
            $this->selectedServices,
        );
    }

    public function render()
    {
        $activeScenario = $this->scenarios[$this->selectedScenario] ?? null;
        $customLines = $this->customShortlistLines($activeScenario);

        return view('livewire.media-advisor', [
            'activeScenario' => $activeScenario,
            'customLines' => $customLines,
            'basketLines' => $this->basketLines(),
            'basketServices' => $this->basketServices(),
            'shareUrl' => $this->token === null ? null : route('advisor.show', ['token' => $this->token]),
            'shortlistTotal' => $this->shortlistTotal($customLines),
        ])->layout('layouts.app', [
            'pageTitle' => 'Media Strategy Studio | Dunia Media',
            'metaDescription' => 'An interactive media planning workbench. Score live Malaysian inventory against your objective, budget, regions and languages, then save a plan or request a proposal.',
        ]);
    }

    private function fillFromSession(AdvisorSession $session): void
    {
        $recommendations = is_array($session->recommendations) ? $session->recommendations : [];

        $this->generationMode = (string) ($session->generation_mode ?: 'catalogue_engine');
        $this->executiveHeadline = (string) ($recommendations['headline'] ?? '');
        $this->executiveDiagnosis = (string) ($recommendations['diagnosis'] ?? '');
        $this->objective = (string) $session->objective;
        $this->budgetAmount = (string) (int) (float) $session->budget_amount;
        $this->budgetFlexibility = (string) $session->budget_flexibility;
        $this->regions = is_array($session->regions) ? array_map('strval', $session->regions) : [];
        $this->languages = is_array($session->languages) ? array_map('strval', $session->languages) : [];
        $this->industry = (string) ($session->industry ?? '');
        $this->audience = (string) ($session->audience ?? '');
        $this->timeline = (string) $session->timeline;
        $this->launchWindow = (string) ($session->launch_window ?? '');
        $this->preferredMediums = is_array($session->preferred_mediums) ? array_map('strval', $session->preferred_mediums) : [];
        $this->creativeReadiness = (string) $session->creative_readiness;
        $this->brandContext = (string) ($session->brand_context ?? '');
        $this->selectedScenario = (string) ($session->selected_scenario_key ?? 'balanced');
        $this->shortlist = is_array($session->customized_product_ids) ? array_map('strval', $session->customized_product_ids) : [];
        $this->selectedServices = is_array($session->customized_services) ? array_map('strval', $session->customized_services) : [];
        $this->planInstance = $session->saved_plan_instance;
        $this->submitted = $session->submitted_at !== null;
        $this->generated = $session->recommendations !== null;
        $this->workspaceTab = $this->generated ? 'strategy' : 'brief';

        $scenarios = $recommendations['scenarios'] ?? null;
        $this->scenarios = is_array($scenarios) ? $scenarios : [];
        $this->conversation = $this->conversationFrom($session);
    }

    private function applySession(AdvisorSession $session): void
    {
        $this->fillFromSession($session);
        $this->generated = true;
        $this->selectedScenario = (string) ($session->selected_scenario_key ?? 'balanced');
        $this->shortlist = $this->scenarioProductIds($this->selectedScenario);
        $this->selectedServices = [];
    }

    /**
     * @return list<string>
     */
    private function scenarioProductIds(string $key): array
    {
        $lines = $this->scenarios[$key]['lines'] ?? null;

        if (! is_array($lines)) {
            return [];
        }

        $ids = [];

        foreach ($lines as $line) {
            if (is_array($line) && isset($line['product_id'])) {
                $ids[] = (string) $line['product_id'];
            }
        }

        return $ids;
    }

    private function saveShortlist(UpdateAdvisorShortlist $action): void
    {
        $session = $this->currentSession();

        if ($session === null) {
            return;
        }

        $action->handle($session, $this->selectedScenario, $this->shortlist, $this->selectedServices);
    }

    private function brief(): AdvisorBriefInput
    {
        return AdvisorBriefInput::fromArray([
            'objective' => $this->objective,
            'budget_amount' => (float) $this->budgetAmount,
            'budget_flexibility' => $this->budgetFlexibility,
            'currency_code' => 'MYR',
            'regions' => $this->regions,
            'languages' => $this->languages,
            'industry' => $this->industry,
            'audience' => $this->audience,
            'timeline' => $this->timeline,
            'launch_window' => $this->launchWindow,
            'preferred_mediums' => $this->preferredMediums,
            'creative_readiness' => $this->creativeReadiness,
            'brand_context' => $this->brandContext,
        ]);
    }

    private function currentSession(): ?AdvisorSession
    {
        if ($this->sessionId === null) {
            return null;
        }

        return AdvisorSession::query()->find($this->sessionId);
    }

    private function sessionIdentifier(): string
    {
        return 'advisor-'.session()->getId();
    }

    /**
     * @return list<array{role: string, content: string, suggestions: list<string>}>
     */
    private function conversationFrom(AdvisorSession $session): array
    {
        return $session->messages()
            ->get()
            ->map(static function (AdvisorMessage $message): array {
                $metadata = is_array($message->metadata) ? $message->metadata : [];
                $suggestions = $metadata['suggestions'] ?? [];

                return [
                    'role' => (string) $message->role,
                    'content' => (string) $message->content,
                    'suggestions' => is_array($suggestions) ? array_map('strval', $suggestions) : [],
                ];
            })
            ->all();
    }

    /**
     * @return list<array{token: string, objective: string, generated_at: ?string}>
     */
    private function recentConsultations(): array
    {
        $recent = session()->get('advisor.recent', []);

        return is_array($recent) ? array_values($recent) : [];
    }

    private function rememberConsultation(AdvisorSession $session): void
    {
        $brief = is_array($session->brief_summary) ? $session->brief_summary : [];

        $entries = array_values(array_filter(
            $this->recentConsultations(),
            static fn (array $entry): bool => $entry['token'] !== $session->share_token,
        ));

        array_unshift($entries, [
            'token' => (string) $session->share_token,
            'objective' => (string) ($brief['objective_label'] ?? $session->objective),
            'generated_at' => $session->generated_at?->toIso8601String(),
        ]);

        $this->recent = array_slice($entries, 0, 5);

        session()->put('advisor.recent', $this->recent);
    }

    /**
     * @param  array<string, mixed>|null  $activeScenario
     * @return list<array<string, mixed>>
     */
    private function customShortlistLines(?array $activeScenario): array
    {
        if ($this->shortlist === []) {
            return [];
        }

        $scenarioIds = [];

        foreach (is_array($activeScenario['lines'] ?? null) ? $activeScenario['lines'] : [] as $line) {
            if (is_array($line) && isset($line['product_id'])) {
                $scenarioIds[(string) $line['product_id']] = true;
            }
        }

        $extraIds = array_values(array_filter(
            $this->shortlist,
            static fn (string $productId): bool => ! isset($scenarioIds[$productId]),
        ));

        return $this->linesForIds($extraIds);
    }

    /**
     * @param  list<string>  $productIds
     * @return list<array<string, mixed>>
     */
    private function linesForIds(array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }

        return array_map(
            static fn (MediaOfferView $offer): array => [
                'product_id' => $offer->id,
                'name' => $offer->name,
                'slug' => $offer->slug,
                'medium' => $offer->medium,
                'location' => $offer->locationLabel,
                'channel' => $offer->channelLabel,
                'role' => 'Added from catalogue',
                'reason' => $offer->summary ?: 'Added manually from the live Dunia Media catalogue.',
                'quantity' => 1,
                'unit_code' => $offer->unitCode,
                'unit_price' => $offer->priceFrom,
                'subtotal' => $offer->priceFrom,
                'on_request' => $offer->priceFrom === null,
                'image' => $offer->imageUrl,
                'seller' => $offer->sellerName,
            ],
            app(PresentsMedia::class)->views($productIds),
        );
    }

    /**
     * @param  list<array<string, mixed>>  $customLines
     * @return array{total: float, on_request: int, known: float, headroom: float}
     */
    private function shortlistTotal(array $customLines = []): array
    {
        $prices = [];

        foreach ($this->scenarios as $scenario) {
            foreach (is_array($scenario['lines'] ?? null) ? $scenario['lines'] : [] as $line) {
                if (is_array($line) && isset($line['product_id'])) {
                    $prices[(string) $line['product_id']] = $line['unit_price'] ?? null;
                }
            }
        }

        foreach ($customLines as $line) {
            if (isset($line['product_id'])) {
                $prices[(string) $line['product_id']] = $line['unit_price'] ?? null;
            }
        }

        $known = 0.0;
        $onRequest = 0;

        foreach ($this->shortlist as $productId) {
            $price = $prices[$productId] ?? null;

            if (is_numeric($price)) {
                $known += (float) $price;
            } else {
                $onRequest++;
            }
        }

        $budget = (float) $this->budgetAmount;

        return [
            'total' => $known,
            'on_request' => $onRequest,
            'known' => $known,
            'headroom' => max(0.0, $budget - $known),
        ];
    }
}
