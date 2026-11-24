<?php

declare(strict_types=1);

namespace App\Features\Advisor\Actions;

use App\Features\Advisor\Domain\AdvisorBriefInput;
use App\Features\Advisor\Domain\ComposeAdvisorScenarios;
use App\Features\Advisor\Domain\ScoreCatalogueForBrief;
use App\Features\Advisor\Models\AdvisorSession;
use App\Features\Advisor\Persistence\WriteAdvisorMessages;
use App\Features\Advisor\Persistence\WriteAdvisorSessions;
use App\Features\Catalog\Api\MediaOfferView;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Clients\Api\ListsCampaigns;
use App\Features\Clients\Api\ListsServices;
use App\Features\Creators\Api\ListsCreators;
use App\Shared\Contracts\AdvisesMediaStrategy;
use App\Shared\Contracts\StrategySynthesisRequest;
use App\Shared\Contracts\StrategySynthesisResult;
use Illuminate\Support\Str;

final readonly class GenerateAdvisorStrategy
{
    public function __construct(
        private PresentsMedia $media,
        private ListsCreators $creators,
        private ListsServices $services,
        private ListsCampaigns $campaigns,
        private ScoreCatalogueForBrief $scorer,
        private ComposeAdvisorScenarios $composer,
        private AdvisesMediaStrategy $strategist,
        private WriteAdvisorSessions $sessions,
        private WriteAdvisorMessages $messages,
    ) {}

    public function handle(AdvisorBriefInput $brief, string $sessionIdentifier, ?AdvisorSession $session = null): AdvisorSession
    {
        $ranked = $this->scorer->rank($this->media->activeOffers(), $brief);

        $recommendations = $this->composer->compose(
            $ranked,
            $brief,
            $this->creators->active(8),
            $this->services->published(),
            $this->campaigns->recent(8),
        );

        $mode = 'catalogue_engine';
        $synthesis = $this->strategist->synthesize($this->synthesisRequest($brief, $ranked));

        if ($synthesis !== null) {
            $recommendations = $this->mergeSynthesis($recommendations, $synthesis);
            $mode = 'ai_assisted';
        }

        $attributes = array_merge($this->briefAttributes($brief), [
            'generation_mode' => $mode,
            'status' => 'generated',
            'brief_summary' => $brief->toArray(),
            'recommendations' => $recommendations,
            'selected_scenario_key' => 'balanced',
            'generated_at' => now(),
        ]);

        if ($session === null) {
            $attributes['share_token'] = $this->uniqueToken();
            $attributes['session_identifier'] = $sessionIdentifier;
            $session = $this->sessions->create($attributes);
        } else {
            $session = $this->sessions->update($session, $attributes);
        }

        $this->messages->create($session, 'assistant', $this->summaryMessage($brief, $recommendations), [
            'kind' => 'strategy_summary',
            'generation_mode' => $mode,
        ]);

        return $session->refresh();
    }

    /**
     * @return array<string, mixed>
     */
    private function briefAttributes(AdvisorBriefInput $brief): array
    {
        return [
            'objective' => $brief->objective->value,
            'budget_amount' => $brief->budgetAmount,
            'budget_flexibility' => $brief->budgetFlexibility->value,
            'currency_code' => $brief->currencyCode,
            'regions' => $brief->regions,
            'languages' => $brief->languages,
            'industry' => $brief->industry,
            'audience' => $brief->audience,
            'timeline' => $brief->timeline->value,
            'launch_window' => $brief->launchWindow,
            'preferred_mediums' => $brief->preferredMediums,
            'creative_readiness' => $brief->creativeReadiness->value,
            'brand_context' => $brief->brandContext,
        ];
    }

    /**
     * @param  list<array{offer: MediaOfferView, score: int, reasons: list<string>}>  $ranked
     */
    private function synthesisRequest(AdvisorBriefInput $brief, array $ranked): StrategySynthesisRequest
    {
        $candidates = [];

        foreach (array_slice($ranked, 0, 20) as $entry) {
            $offer = $entry['offer'];

            $candidates[] = [
                'product_id' => $offer->id,
                'name' => $offer->name,
                'medium' => $offer->medium,
                'location' => $offer->locationLabel,
                'channel' => $offer->channelLabel,
                'audience' => $offer->audienceLabel,
                'price_from' => $offer->priceFrom,
                'unit_code' => $offer->unitCode,
            ];
        }

        return new StrategySynthesisRequest(
            objective: $brief->objective->value,
            budgetAmount: $brief->budgetAmount,
            currencyCode: $brief->currencyCode,
            regions: $brief->regions,
            languages: $brief->languages,
            industry: $brief->industry,
            audience: $brief->audience,
            timeline: $brief->timeline->value,
            launchWindow: $brief->launchWindow,
            preferredMediums: $brief->preferredMediums,
            creativeReadiness: $brief->creativeReadiness->value,
            brandContext: $brief->brandContext,
            candidates: $candidates,
        );
    }

    /**
     * @param  array<string, mixed>  $recommendations
     * @return array<string, mixed>
     */
    private function mergeSynthesis(array $recommendations, StrategySynthesisResult $synthesis): array
    {
        $recommendations['headline'] = $synthesis->headline;
        $recommendations['diagnosis'] = $synthesis->diagnosis;
        $recommendations['questions'] = $synthesis->questions;
        $recommendations['ai_assisted'] = true;

        $scenarios = is_array($recommendations['scenarios'] ?? null) ? $recommendations['scenarios'] : [];

        foreach ($synthesis->scenarios as $key => $enrichment) {
            if (! is_array($scenarios[$key] ?? null)) {
                continue;
            }

            $scenarios[$key]['name'] = $enrichment['name'] !== '' ? $enrichment['name'] : $scenarios[$key]['name'];
            $scenarios[$key]['headline'] = $enrichment['headline'] !== '' ? $enrichment['headline'] : $scenarios[$key]['headline'];
            $scenarios[$key]['messaging'] = $enrichment['messaging'] !== '' ? $enrichment['messaging'] : $scenarios[$key]['messaging'];
            $scenarios[$key]['creative_angle'] = $enrichment['creative_angle'] !== '' ? $enrichment['creative_angle'] : $scenarios[$key]['creative_angle'];
            $scenarios[$key]['diagnosis'] = $enrichment['diagnosis'] !== '' ? $enrichment['diagnosis'] : $scenarios[$key]['diagnosis'];
            $scenarios[$key]['ai_product_ids'] = $enrichment['product_ids'];
        }

        $recommendations['scenarios'] = $scenarios;

        return $recommendations;
    }

    /**
     * @param  array<string, mixed>  $recommendations
     */
    private function summaryMessage(AdvisorBriefInput $brief, array $recommendations): string
    {
        $scenarios = is_array($recommendations['scenarios'] ?? null) ? $recommendations['scenarios'] : [];
        $names = [];

        foreach ($scenarios as $scenario) {
            if (is_array($scenario)) {
                $names[] = (string) $scenario['name'].' ('.(string) $scenario['estimated_label'].')';
            }
        }

        $prefix = ($recommendations['ai_assisted'] ?? false) ? 'AI-assisted strategy' : 'Catalogue strategy';

        return $prefix.' ready for a '.$brief->objective->label().' brief with RM '.number_format($brief->budgetAmount).'. Three scenarios: '.implode(', ', $names).'. Compare them, toggle opportunities on the shortlist, then save a plan or request a senior planner review.';
    }

    private function uniqueToken(): string
    {
        do {
            $token = 'ADV-'.mb_strtoupper(Str::random(8));
        } while (AdvisorSession::query()->where('share_token', $token)->exists());

        return $token;
    }
}
