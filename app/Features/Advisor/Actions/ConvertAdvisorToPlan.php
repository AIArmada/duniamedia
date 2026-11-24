<?php

declare(strict_types=1);

namespace App\Features\Advisor\Actions;

use App\Features\Advisor\Models\AdvisorSession;
use App\Features\Advisor\Persistence\WriteAdvisorSessions;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Sales\Api\CampaignPlanReceipt;
use App\Features\Sales\Api\CreatesCampaignPlans;
use InvalidArgumentException;

final readonly class ConvertAdvisorToPlan
{
    public function __construct(
        private PresentsMedia $media,
        private CreatesCampaignPlans $plans,
        private WriteAdvisorSessions $sessions,
    ) {}

    public function handle(AdvisorSession $session): CampaignPlanReceipt
    {
        $productIds = $this->activeProductIds($session);

        if ($productIds === []) {
            throw new InvalidArgumentException('Select at least one opportunity before saving a campaign plan.');
        }

        $items = array_map(static fn (string $productId): array => ['product_id' => $productId, 'quantity' => 1], $productIds);

        $receipt = $this->plans->createFromOfferings($this->planName($session), $items, $this->metadata($session));

        $this->sessions->update($session, [
            'saved_plan_instance' => $receipt->instance,
            'status' => 'planned',
        ]);

        return $receipt;
    }

    /**
     * @return list<string>
     */
    public function activeProductIds(AdvisorSession $session): array
    {
        $ids = [];

        foreach (is_array($session->customized_product_ids) ? $session->customized_product_ids : [] as $productId) {
            if (is_string($productId) && $this->media->contains($productId)) {
                $ids[] = $productId;
            }
        }

        if ($ids !== []) {
            return array_values(array_unique($ids));
        }

        foreach ($this->scenarioLines($session) as $line) {
            $productId = $line['product_id'] ?? null;

            if (is_string($productId) && $this->media->contains($productId)) {
                $ids[] = $productId;
            }
        }

        return array_values(array_unique($ids));
    }

    private function planName(AdvisorSession $session): string
    {
        $brief = is_array($session->brief_summary) ? $session->brief_summary : [];
        $objective = (string) ($brief['objective_label'] ?? 'Media advisor');
        $region = is_array($brief['regions'] ?? null) && $brief['regions'] !== [] ? (string) $brief['regions'][0] : 'Malaysia';

        return 'Media Advisor · '.$objective.' · '.$region;
    }

    /**
     * @return array<string, mixed>
     */
    private function metadata(AdvisorSession $session): array
    {
        $brief = is_array($session->brief_summary) ? $session->brief_summary : [];
        $regions = is_array($brief['regions'] ?? null) ? array_map('strval', $brief['regions']) : [];
        $languages = is_array($brief['languages'] ?? null) ? array_map('strval', $brief['languages']) : [];

        return [
            'objective' => (string) ($brief['objective_label'] ?? $session->objective),
            'brief' => $this->briefText($session),
            'budget_amount' => (float) $session->budget_amount,
            'currency_code' => (string) $session->currency_code,
            'regions' => $regions,
            'languages' => $languages,
            'timeline' => (string) $session->timeline,
            'launch_window' => $session->launch_window,
            'advisor_reference' => (string) $session->share_token,
            'advisor_scenario' => (string) ($session->selected_scenario_key ?? 'balanced'),
            'advisor_services' => is_array($session->customized_services) ? $session->customized_services : [],
            'source' => 'media_advisor',
        ];
    }

    private function briefText(AdvisorSession $session): string
    {
        $brief = is_array($session->brief_summary) ? $session->brief_summary : [];
        $parts = [
            'Objective: '.(string) ($brief['objective_label'] ?? $session->objective),
            'Budget: RM '.number_format((float) $session->budget_amount),
        ];

        if (is_array($brief['regions'] ?? null) && $brief['regions'] !== []) {
            $parts[] = 'Regions: '.implode(', ', array_map('strval', $brief['regions']));
        }

        if (is_array($brief['languages'] ?? null) && $brief['languages'] !== []) {
            $parts[] = 'Languages: '.implode(', ', array_map('strval', $brief['languages']));
        }

        $parts[] = 'Timeline: '.(string) ($brief['timeline_label'] ?? $session->timeline);

        if ($session->brand_context !== null) {
            $parts[] = 'Context: '.$session->brand_context;
        }

        return implode('. ', $parts).'.';
    }

    /**
     * @return list<array<array-key, mixed>>
     */
    private function scenarioLines(AdvisorSession $session): array
    {
        $recommendations = is_array($session->recommendations) ? $session->recommendations : [];
        $scenarios = $recommendations['scenarios'] ?? null;
        $key = $session->selected_scenario_key ?? 'balanced';

        if (! is_array($scenarios) || ! is_array($scenarios[$key] ?? null)) {
            return [];
        }

        $lines = $scenarios[$key]['lines'] ?? null;

        return is_array($lines) ? array_values(array_filter($lines, 'is_array')) : [];
    }
}
