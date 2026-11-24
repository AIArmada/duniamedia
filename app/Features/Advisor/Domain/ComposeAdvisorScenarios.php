<?php

declare(strict_types=1);

namespace App\Features\Advisor\Domain;

use App\Features\Catalog\Api\MediaOfferView;
use App\Features\Clients\Api\ServiceSummary;
use App\Features\Creators\Api\CreatorSummary;

final class ComposeAdvisorScenarios
{
    /** @var list<string> */
    private const ROLES = ['Anchor reach', 'Supporting frequency', 'Audience depth', 'Conversion push', 'Amplification'];

    /**
     * @param  list<array{offer: MediaOfferView, score: int, reasons: list<string>}>  $ranked
     * @param  list<CreatorSummary>  $creators
     * @param  list<ServiceSummary>  $services
     * @param  list<array{id: string, title: string, description: ?string, medium: ?string, channel: ?string, date: ?string, year: int, client_id: ?string}>  $campaigns
     * @return array{headline: string, diagnosis: string, questions: list<string>, scenarios: array<string, array<string, mixed>>, stats: array<string, mixed>}
     */
    public function compose(array $ranked, AdvisorBriefInput $brief, array $creators, array $services, array $campaigns): array
    {
        $definitions = [
            ['key' => 'lean', 'name' => 'Focused & Agile', 'tagline' => 'Lean, efficient and fast to flight', 'budget_target' => 0.78, 'min' => 2, 'max' => 3, 'per_medium' => 1],
            ['key' => 'balanced', 'name' => 'Connected Multi-Channel', 'tagline' => 'The connected recommendation', 'budget_target' => 0.95, 'min' => 3, 'max' => 4, 'per_medium' => 2],
            ['key' => 'impact', 'name' => 'High-Impact Reach', 'tagline' => 'Flagship reach and presence', 'budget_target' => 1.12, 'min' => 4, 'max' => 5, 'per_medium' => 3],
        ];

        $scenarios = [];

        foreach ($definitions as $definition) {
            $picked = $this->pick($ranked, $brief, $definition);

            $scenarios[$definition['key']] = $this->buildScenario(
                $definition,
                $picked,
                $brief,
                $creators,
                $services,
                $campaigns,
            );
        }

        return [
            'headline' => 'A '.$brief->objective->label().' plan for '.($brief->regions[0] ?? 'Malaysia').' with RM '.number_format($brief->budgetAmount).' to deploy.',
            'diagnosis' => 'Scored '.count($ranked).' live catalogue opportunities against your objective, regions, languages, budget and channel preferences. Each scenario below is composed from real, currently active inventory.',
            'questions' => [
                'Which owners can confirm the exact flight dates you need?',
                'How should creative adaptation and production lead time be scheduled?',
                'Who owns measurement, lead handling and reporting after flight?',
            ],
            'scenarios' => $scenarios,
            'stats' => [
                'catalogue_scored' => count($ranked),
                'catalogue_size' => count($ranked),
            ],
        ];
    }

    /**
     * @param  list<array{offer: MediaOfferView, score: int, reasons: list<string>}>  $ranked
     * @param  array{key: string, name: string, tagline: string, budget_target: float, min: int, max: int, per_medium: int}  $definition
     * @return list<array{offer: MediaOfferView, score: int, reasons: list<string>}>
     */
    private function pick(array $ranked, AdvisorBriefInput $brief, array $definition): array
    {
        $picked = [];
        $perMedium = [];
        $estimated = 0.0;
        $ceiling = max(1.0, $brief->budgetAmount) * $definition['budget_target'] * 1.25;

        foreach ($ranked as $entry) {
            if (count($picked) >= $definition['max']) {
                break;
            }

            $medium = $entry['offer']->medium ?? 'Other';

            if (($perMedium[$medium] ?? 0) >= $definition['per_medium']) {
                continue;
            }

            $cost = $entry['offer']->priceFrom ?? ($brief->budgetAmount * 0.18);

            if (count($picked) >= $definition['min'] && $estimated + $cost > $ceiling) {
                continue;
            }

            $picked[] = $entry;
            $perMedium[$medium] = ($perMedium[$medium] ?? 0) + 1;
            $estimated += $cost;

            if ($estimated >= $brief->budgetAmount * $definition['budget_target'] && count($picked) >= $definition['min']) {
                break;
            }
        }

        if (count($picked) < $definition['min']) {
            $chosen = array_map(static fn (array $entry): string => $entry['offer']->id, $picked);

            foreach ($ranked as $entry) {
                if (count($picked) >= $definition['min']) {
                    break;
                }

                if (in_array($entry['offer']->id, $chosen, true)) {
                    continue;
                }

                $picked[] = $entry;
                $chosen[] = $entry['offer']->id;
            }
        }

        return $picked;
    }

    /**
     * @param  array{key: string, name: string, tagline: string, budget_target: float, min: int, max: int, per_medium: int}  $definition
     * @param  list<array{offer: MediaOfferView, score: int, reasons: list<string>}>  $picked
     * @param  list<CreatorSummary>  $creators
     * @param  list<ServiceSummary>  $services
     * @param  list<array{id: string, title: string, description: ?string, medium: ?string, channel: ?string, date: ?string, year: int, client_id: ?string}>  $campaigns
     * @return array<string, mixed>
     */
    private function buildScenario(array $definition, array $picked, AdvisorBriefInput $brief, array $creators, array $services, array $campaigns): array
    {
        $lines = [];
        $costs = [];
        $byMedium = [];
        $estimated = 0.0;
        $onRequest = 0;

        foreach ($picked as $index => $entry) {
            $offer = $entry['offer'];
            $cost = $offer->priceFrom ?? ($brief->budgetAmount * 0.18);
            $costs[$index] = $cost;
            $estimated += $cost;
            $medium = $offer->medium ?? 'Other';
            $byMedium[$medium] = ($byMedium[$medium] ?? 0) + $cost;

            if ($offer->priceFrom === null) {
                $onRequest++;
            }

            $lines[] = [
                'product_id' => $offer->id,
                'name' => $offer->name,
                'slug' => $offer->slug,
                'medium' => $offer->medium,
                'location' => $offer->locationLabel,
                'channel' => $offer->channelLabel,
                'role' => self::ROLES[$index] ?? 'Support',
                'reason' => $this->lineReason($entry, $brief),
                'quantity' => 1,
                'unit_code' => $offer->unitCode,
                'unit_price' => $offer->priceFrom,
                'subtotal' => $offer->priceFrom,
                'on_request' => $offer->priceFrom === null,
                'image' => $offer->imageUrl,
                'seller' => $offer->sellerName,
            ];
        }

        $total = array_sum($byMedium) ?: 1.0;
        $mix = [];

        foreach ($byMedium as $medium => $amount) {
            $mix[] = [
                'medium' => $medium,
                'share' => (int) round($amount / $total * 100),
                'amount' => round($amount, 2),
                'on_request' => false,
            ];
        }

        usort($mix, static fn (array $left, array $right): int => $right['share'] <=> $left['share']);

        $headroom = max(0.0, $brief->budgetAmount - $estimated);
        $mediums = array_values(array_unique(array_map(static fn (array $line): string => (string) $line['medium'], $lines)));

        return [
            'key' => $definition['key'],
            'name' => $definition['name'],
            'tagline' => $definition['tagline'],
            'headline' => $definition['name'].' — '.$definition['tagline'],
            'messaging' => 'Lead with '.$brief->objective->label().' across '.implode(', ', array_slice($mediums, 0, 3)).'.',
            'creative_angle' => $this->creativeAngle($brief, $mediums),
            'diagnosis' => 'A '.count($lines).'-line mix of '.count($mediums).' mediums with an indicative subtotal of RM '.number_format($estimated).'.',
            'budget_target' => $definition['budget_target'],
            'budget_target_label' => '≈ '.round($definition['budget_target'] * 100).'% of budget',
            'estimated_subtotal' => round($estimated, 2),
            'estimated_label' => 'RM '.number_format($estimated).' indicative',
            'on_request_count' => $onRequest,
            'headroom' => round($headroom, 2),
            'headroom_label' => 'RM '.number_format($headroom).' headroom',
            'lines' => $lines,
            'channel_mix' => $mix,
            'mix_label' => implode(' · ', array_map(static fn (array $row): string => $row['share'].'% '.$row['medium'], $mix)),
            'creators' => $this->matchCreators($creators, $brief, $lines),
            'services' => $this->matchServices($services, $brief, $lines),
            'past_work' => $this->matchCampaigns($campaigns, $lines),
            'roadmap' => $this->roadmap($brief),
            'verification' => $this->verification($brief),
            'diagnostics' => $this->diagnostics($brief, $picked, $estimated, $onRequest, $mediums),
        ];
    }

    /**
     * @param  array{offer: MediaOfferView, score: int, reasons: list<string>}  $entry
     */
    private function lineReason(array $entry, AdvisorBriefInput $brief): string
    {
        $reasons = array_slice($entry['reasons'], 0, 2);

        if ($reasons === []) {
            return 'Recommended for a '.$brief->objective->label().' objective';
        }

        return implode(' · ', $reasons);
    }

    /**
     * @param  list<string>  $mediums
     */
    private function creativeAngle(AdvisorBriefInput $brief, array $mediums): string
    {
        return match ($brief->creativeReadiness) {
            CreativeReadiness::Ready => 'Repurpose your existing master creative across '.implode(', ', array_slice($mediums, 0, 3)).'.',
            CreativeReadiness::NeedAdaptation => 'Adapt the master creative into format-specific cutdowns for '.implode(', ', array_slice($mediums, 0, 3)).'.',
            CreativeReadiness::NeedFullProduction => 'Commission a production package first, then flight across '.implode(', ', array_slice($mediums, 0, 3)).'.',
        };
    }

    /**
     * @param  list<CreatorSummary>  $creators
     * @param  list<array<string, mixed>>  $lines
     * @return list<array<string, mixed>>
     */
    private function matchCreators(array $creators, AdvisorBriefInput $brief, array $lines): array
    {
        $wantsCreators = in_array('Creators', array_map(static fn (array $line): string => (string) $line['medium'], $lines), true);

        $matched = [];
        $fallback = [];

        foreach ($creators as $creator) {
            $fallback[] = $creator;

            $niche = mb_strtolower($creator->niche ?? '');

            if ($wantsCreators || $niche !== '') {
                $matched[] = $creator;
            }

            if (count($matched) >= 3) {
                break;
            }
        }

        $selection = $matched !== [] ? $matched : array_slice($fallback, 0, 3);

        return array_map(static fn (CreatorSummary $creator): array => [
            'id' => $creator->id,
            'name' => $creator->name,
            'slug' => $creator->slug,
            'niche' => $creator->niche,
            'platform' => $creator->socialPlatform,
            'handle' => $creator->handle,
            'image' => $creator->imageUrl,
        ], $selection);
    }

    /**
     * @param  list<ServiceSummary>  $services
     * @param  list<array<string, mixed>>  $lines
     * @return list<array<string, mixed>>
     */
    private function matchServices(array $services, AdvisorBriefInput $brief, array $lines): array
    {
        $wanted = ['media planning'];

        if ($brief->creativeReadiness !== CreativeReadiness::Ready) {
            $wanted[] = 'creative';
            $wanted[] = 'ai video';
        }

        if (in_array($brief->objective, [CampaignObjective::Leads, CampaignObjective::Sales], true)) {
            $wanted[] = 'conversion';
            $wanted[] = 'analytics';
        }

        if (in_array('Creators', array_map(static fn (array $line): string => (string) $line['medium'], $lines), true)) {
            $wanted[] = 'creator';
        }

        $matched = [];
        $fallback = [];

        foreach ($services as $service) {
            $fallback[] = $service;
            $haystack = mb_strtolower($service->name.' '.($service->category ?? ''));

            foreach ($wanted as $keyword) {
                if (str_contains($haystack, $keyword)) {
                    $matched[$service->id] = $service;

                    break;
                }
            }
        }

        $selection = array_slice(array_values($matched), 0, 4);

        if ($selection === []) {
            $selection = array_slice($fallback, 0, 4);
        }

        return array_map(static fn (ServiceSummary $service): array => [
            'id' => $service->id,
            'slug' => $service->slug,
            'name' => $service->name,
            'category' => $service->category,
            'summary' => $service->summary,
        ], $selection);
    }

    /**
     * @param  list<array{id: string, title: string, description: ?string, medium: ?string, channel: ?string, date: ?string, year: int, client_id: ?string}>  $campaigns
     * @param  list<array<string, mixed>>  $lines
     * @return list<array<string, mixed>>
     */
    private function matchCampaigns(array $campaigns, array $lines): array
    {
        $mediums = array_map(static fn (array $line): string => (string) $line['medium'], $lines);
        $matched = [];
        $fallback = [];

        foreach ($campaigns as $campaign) {
            $fallback[] = $campaign;
            $medium = (string) ($campaign['medium'] ?? '');

            if ($medium !== '' && in_array($medium, $mediums, true)) {
                $matched[] = $campaign;
            }
        }

        $selection = $matched !== [] ? $matched : $fallback;

        return array_map(static fn (array $campaign): array => [
            'id' => $campaign['id'],
            'title' => $campaign['title'],
            'medium' => $campaign['medium'],
            'channel' => $campaign['channel'],
            'date' => $campaign['date'],
            'year' => $campaign['year'],
            'description' => $campaign['description'],
        ], array_slice($selection, 0, 3));
    }

    /**
     * @return list<array{phase: string, window: string, focus: string, items: list<string>}>
     */
    private function roadmap(AdvisorBriefInput $brief): array
    {
        return match ($brief->timeline) {
            CampaignTimeline::WithinFourWeeks => [
                ['phase' => 'Phase 1 · Lock and load', 'window' => 'Week 1', 'focus' => 'Confirm owners, rates and formats', 'items' => ['Verify availability', 'Approve budget split', 'Lock creative formats']],
                ['phase' => 'Phase 2 · Flight', 'window' => 'Weeks 2-3', 'focus' => 'Launch and hold frequency', 'items' => ['Go live across channels', 'Monitor delivery', 'Amplify winning lines']],
                ['phase' => 'Phase 3 · Optimise', 'window' => 'Week 4', 'focus' => 'Read and adjust', 'items' => ['Performance review', 'Reallocate to best performers', 'Wrap report']],
            ],
            CampaignTimeline::OneToThreeMonths => [
                ['phase' => 'Phase 1 · Prepare', 'window' => 'Weeks 1-3', 'focus' => 'Planning, production and booking', 'items' => ['Confirm inventory', 'Produce adaptations', 'Book flights']],
                ['phase' => 'Phase 2 · Flight', 'window' => 'Months 1-2', 'focus' => 'Sustained multi-channel presence', 'items' => ['Launch all channels', 'Mid-flight optimisation', 'Creator activations']],
                ['phase' => 'Phase 3 · Sustain', 'window' => 'Month 3', 'focus' => 'Consolidate and measure', 'items' => ['Boost best lines', 'Lead follow-up', 'Consolidated reporting']],
            ],
            CampaignTimeline::ThreeToSixMonths => [
                ['phase' => 'Phase 1 · Prepare', 'window' => 'Month 1', 'focus' => 'Strategy, production and bookings', 'items' => ['Owner negotiation', 'Creative production', 'Measurement setup']],
                ['phase' => 'Phase 2 · Flight', 'window' => 'Months 2-4', 'focus' => 'Build reach and frequency', 'items' => ['Steady-state delivery', 'Audience optimisation', 'Content refresh']],
                ['phase' => 'Phase 3 · Amplify', 'window' => 'Months 5-6', 'focus' => 'Scale what works', 'items' => ['Scale winning formats', 'Add creator layer', 'Full measurement review']],
            ],
            CampaignTimeline::Ongoing => [
                ['phase' => 'Phase 1 · Prepare', 'window' => 'Month 1', 'focus' => 'Set always-on foundations', 'items' => ['Define KPIs', 'Set up tracking', 'Book evergreen inventory']],
                ['phase' => 'Phase 2 · Always-on', 'window' => 'Monthly', 'focus' => 'Continuous delivery and refinement', 'items' => ['Monthly media delivery', 'Creative rotation', 'Performance tuning']],
                ['phase' => 'Phase 3 · Review', 'window' => 'Quarterly', 'focus' => 'Strategic review and reinvestment', 'items' => ['Quarterly business review', 'Budget reallocation', 'Roadmap refresh']],
            ],
        };
    }

    /**
     * @return list<string>
     */
    private function verification(AdvisorBriefInput $brief): array
    {
        $items = [
            '8% SST applies to media and production.',
            'Festive or peak-season loading may apply to premium inventory.',
            'Production lead time of 2 to 3 weeks applies before flight.',
            'Owner availability and blackout dates must be confirmed before booking.',
        ];

        if ($brief->creativeReadiness !== CreativeReadiness::Ready) {
            $items[] = 'Creative production is required before the flight window.';
        }

        return $items;
    }

    /**
     * @param  list<array{offer: MediaOfferView, score: int, reasons: list<string>}>  $picked
     * @param  list<string>  $mediums
     * @return list<string>
     */
    private function diagnostics(AdvisorBriefInput $brief, array $picked, float $estimated, int $onRequest, array $mediums): array
    {
        $diagnostics = [];

        if ($picked === []) {
            $diagnostics[] = 'No active catalogue opportunities matched this brief yet. Widen regions or budget to surface options.';
        }

        if ($estimated > $brief->budgetAmount * 1.05 && $brief->budgetAmount > 0) {
            $diagnostics[] = 'The indicative mix of RM '.number_format($estimated).' sits above your RM '.number_format($brief->budgetAmount).' budget — trim or phase the plan.';
        }

        if ($onRequest > 0) {
            $diagnostics[] = $onRequest.' line(s) are priced on request and need confirmed owner rates.';
        }

        if ($brief->preferredMediums !== []) {
            $unused = array_diff($brief->preferredMediums, $mediums);

            if ($unused !== []) {
                $diagnostics[] = 'Preferred mediums not used by this scenario: '.implode(', ', $unused).'. Some flexibility may help the split.';
            }
        }

        if ($brief->timeline === CampaignTimeline::WithinFourWeeks && $brief->creativeReadiness === CreativeReadiness::NeedFullProduction) {
            $diagnostics[] = 'Launching within four weeks while needing full production is tight — start with fast-turnaround digital or AI creative while broadcast and outdoor clear.';
        }

        if ($brief->launchWindow !== null && preg_match('/raya|ramadan|cny|chinese new year|deepavali|merdeka|gawai/i', $brief->launchWindow) === 1) {
            $diagnostics[] = 'Festive windows ('.$brief->launchWindow.') fill early and often carry seasonal loading — lock priority inventory ahead of schedule.';
        }

        if ($brief->languages === []) {
            $diagnostics[] = 'No languages were selected — audience targeting will lean on region and channel data.';
        }

        return $diagnostics;
    }
}
