<?php

declare(strict_types=1);

namespace App\Features\Advisor\Domain;

final class DeterministicFollowUpResponder
{
    /**
     * @param  array<array-key, mixed>  $brief
     * @param  array<array-key, mixed>  $scenarios
     * @return array{reply: string, suggestions: list<string>}
     */
    public function respond(string $question, array $brief, array $scenarios): array
    {
        $prompt = mb_strtolower($question);

        return match (true) {
            $this->containsAny($prompt, ['budget', 'cheaper', 'reduce', 'cost', 'cheap', 'price', 'spend']) => $this->budgetReply($brief, $scenarios),
            $this->containsAny($prompt, ['radio', 'tv', 'broadcast', 'outdoor', 'dooh', 'digital', 'print', 'medium', 'channel', 'mix']) => $this->mediumReply($scenarios),
            $this->containsAny($prompt, ['language', 'malay', 'english', 'chinese', 'mandarin', 'tamil', 'audience']) => $this->languageReply($brief),
            $this->containsAny($prompt, ['timeline', 'when', 'date', 'week', 'month', 'schedule', 'flight', 'launch']) => $this->timelineReply($brief, $scenarios),
            $this->containsAny($prompt, ['sst', 'tax', 'gst', 'invoice', 'quote', 'quotation']) => $this->commercialReply(),
            $this->containsAny($prompt, ['book', 'available', 'availability', 'inventory', 'reserve']) => $this->availabilityReply(),
            $this->containsAny($prompt, ['creator', 'influencer', 'kol', 'talent']) => $this->creatorReply($scenarios),
            default => $this->summaryReply($brief, $scenarios),
        };
    }

    /**
     * @param  array<array-key, mixed>  $brief
     * @param  array<array-key, mixed>  $scenarios
     * @return array{reply: string, suggestions: list<string>}
     */
    private function budgetReply(array $brief, array $scenarios): array
    {
        $budget = (float) ($brief['budget_amount'] ?? 0);
        $lean = $scenarios['lean'] ?? null;

        if (! is_array($lean)) {
            return [
                'reply' => 'There is no generated scenario to trim yet. Generate a strategy first, then I can tighten the mix around RM '.number_format($budget).'.',
                'suggestions' => ['Generate the strategy', 'Show the balanced mix'],
            ];
        }

        $lines = is_array($lean['lines'] ?? null) ? $lean['lines'] : [];

        return [
            'reply' => 'The lean scenario — '.(string) $lean['name'].' ('.(string) $lean['estimated_label'].') — targets about '.round(((float) $lean['budget_target']) * 100).'% of your RM '.number_format($budget).' budget, leaving '.(string) $lean['headroom_label'].'. It keeps '.count($lines).' lines across your strongest channels. If you want to go further, we can drop the weakest line or move on-request items.',
            'suggestions' => ['Show the lean mix', 'Compare all three scenarios', 'Raise the budget by 20%'],
        ];
    }

    /**
     * @param  array<array-key, mixed>  $scenarios
     * @return array{reply: string, suggestions: list<string>}
     */
    private function mediumReply(array $scenarios): array
    {
        $parts = [];

        foreach ($scenarios as $scenario) {
            if (is_array($scenario)) {
                $parts[] = (string) $scenario['name'].': '.(string) $scenario['mix_label'];
            }
        }

        if ($parts === []) {
            return [
                'reply' => 'Generate a strategy first and I will break down the channel mix line by line.',
                'suggestions' => ['Generate the strategy'],
            ];
        }

        return [
            'reply' => 'Here is the current channel mix across scenarios. '.implode(' | ', $parts).'. You can toggle individual opportunities on or off in the shortlist and the mix recalculates.',
            'suggestions' => ['Show the balanced mix', 'Make it more efficient', 'Add a creator layer'],
        ];
    }

    /**
     * @param  array<array-key, mixed>  $brief
     * @return array{reply: string, suggestions: list<string>}
     */
    private function languageReply(array $brief): array
    {
        $languages = $brief['languages'] ?? [];
        $regions = $brief['regions'] ?? [];
        $languageLabel = is_array($languages) && $languages !== [] ? implode(', ', array_map('strval', $languages)) : 'not yet selected';
        $regionLabel = is_array($regions) && $regions !== [] ? implode(', ', array_map('strval', $regions)) : 'nationwide';

        return [
            'reply' => 'This brief targets '.$languageLabel.' audience(s) across '.$regionLabel.'. The scored shortlist already weights channels that reach those language groups, so switching language focus mainly reorders the recommendations.',
            'suggestions' => ['Add Tamil audience', 'Focus on Chinese audience', 'Broaden to nationwide'],
        ];
    }

    /**
     * @param  array<array-key, mixed>  $brief
     * @param  array<array-key, mixed>  $scenarios
     * @return array{reply: string, suggestions: list<string>}
     */
    private function timelineReply(array $brief, array $scenarios): array
    {
        $timeline = (string) ($brief['timeline_label'] ?? 'the selected timeline');
        $balanced = $scenarios['balanced'] ?? null;
        $roadmap = is_array($balanced) && is_array($balanced['roadmap'] ?? null) ? $balanced['roadmap'] : [];
        $phases = [];

        foreach ($roadmap as $phase) {
            if (is_array($phase)) {
                $phases[] = (string) $phase['window'];
            }
        }

        $window = $phases !== [] ? ' The flight plan runs '.implode(' then ', $phases).'.' : '';

        return [
            'reply' => 'Your timeline is '.$timeline.'.'.$window.' Production lead time of two to three weeks sits before flight, so lock creative early to protect the launch date.',
            'suggestions' => ['Compress to four weeks', 'Extend to six months', 'Show the flight roadmap'],
        ];
    }

    /**
     * @return array{reply: string, suggestions: list<string>}
     */
    private function commercialReply(): array
    {
        return [
            'reply' => 'Indicative subtotals exclude 8% SST and any festive or peak-season loading. Nothing is booked or charged here — the next step is a proposal where confirmed rates and taxes are applied.',
            'suggestions' => ['Continue to proposal builder', 'Show the verification checklist'],
        ];
    }

    /**
     * @return array{reply: string, suggestions: list<string>}
     */
    private function availabilityReply(): array
    {
        return [
            'reply' => 'Availability is shown per opportunity but must be confirmed with each owner. Save the mix as a campaign plan, then the team verifies inventory and holds the dates with the owners before booking.',
            'suggestions' => ['Save as campaign plan', 'Submit for senior planner review'],
        ];
    }

    /**
     * @param  array<array-key, mixed>  $scenarios
     * @return array{reply: string, suggestions: list<string>}
     */
    private function creatorReply(array $scenarios): array
    {
        $names = [];

        foreach ($scenarios as $scenario) {
            $creators = is_array($scenario) && is_array($scenario['creators'] ?? null) ? $scenario['creators'] : [];

            foreach ($creators as $creator) {
                if (is_array($creator) && isset($creator['name'])) {
                    $names[] = (string) $creator['name'];
                }
            }
        }

        $names = array_values(array_unique($names));

        return [
            'reply' => $names === []
                ? 'No verified creators matched this brief yet. Widen the regions or add a Creators channel to surface the network.'
                : 'Verified creators matched to this brief include '.implode(', ', array_slice($names, 0, 5)).'. They can be briefed for native content and amplification alongside the paid mix.',
            'suggestions' => ['Add a creator layer', 'Show matched creators', 'Compare all three scenarios'],
        ];
    }

    /**
     * @param  array<array-key, mixed>  $brief
     * @param  array<array-key, mixed>  $scenarios
     * @return array{reply: string, suggestions: list<string>}
     */
    private function summaryReply(array $brief, array $scenarios): array
    {
        $objective = (string) ($brief['objective_label'] ?? 'the current objective');
        $budget = (float) ($brief['budget_amount'] ?? 0);

        $parts = [];

        foreach ($scenarios as $scenario) {
            if (is_array($scenario)) {
                $parts[] = (string) $scenario['name'].' ('.(string) $scenario['estimated_label'].')';
            }
        }

        return [
            'reply' => 'For '.$objective.' with RM '.number_format($budget).', the scenarios are: '.implode(', ', $parts).'. Ask me about budget, channel mix, timeline, creators, availability or commercial terms and I will refine the answer.',
            'suggestions' => ['Make it more efficient', 'Show the channel mix', 'What about SST?'],
        ];
    }

    /**
     * @param  list<string>  $needles
     */
    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}
