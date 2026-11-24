<?php

declare(strict_types=1);

namespace App\Features\Advisor\Actions;

use App\Features\Advisor\Domain\DeterministicFollowUpResponder;
use App\Features\Advisor\Models\AdvisorMessage;
use App\Features\Advisor\Models\AdvisorSession;
use App\Features\Advisor\Persistence\WriteAdvisorMessages;
use App\Shared\Contracts\AdvisesMediaStrategy;
use App\Shared\Contracts\AdvisorFollowUpRequest;

final readonly class RefineAdvisorSession
{
    public function __construct(
        private AdvisesMediaStrategy $strategist,
        private DeterministicFollowUpResponder $responder,
        private WriteAdvisorMessages $messages,
    ) {}

    public function handle(AdvisorSession $session, string $question): AdvisorSession
    {
        $question = trim($question);

        if ($question === '') {
            return $session;
        }

        $this->messages->create($session, 'user', $question);

        $brief = is_array($session->brief_summary) ? $session->brief_summary : [];
        $recommendations = is_array($session->recommendations) ? $session->recommendations : [];
        $scenarios = is_array($recommendations['scenarios'] ?? null) ? $recommendations['scenarios'] : [];

        $deterministic = $this->responder->respond($question, $brief, $scenarios);

        $result = $this->strategist->answerFollowUp(new AdvisorFollowUpRequest(
            question: $question,
            brief: $brief,
            history: $this->history($session),
            candidates: $this->candidates($scenarios),
            scenarios: $this->scenarioSummaries($scenarios),
        ));

        $reply = $deterministic['reply'];
        $suggestions = $deterministic['suggestions'];

        if ($result !== null) {
            $reply = $result->reply;
            $suggestions = $result->suggestions;
        }

        $this->messages->create($session, 'assistant', $reply, [
            'kind' => 'follow_up',
            'suggestions' => $suggestions,
            'ai_assisted' => $result !== null,
        ]);

        return $session->refresh();
    }

    /**
     * @return list<array{role: string, content: string}>
     */
    private function history(AdvisorSession $session): array
    {
        return $session->messages()
            ->get()
            ->map(static fn (AdvisorMessage $message): array => [
                'role' => $message->role,
                'content' => $message->content,
            ])
            ->all();
    }

    /**
     * @param  array<string, mixed>  $scenarios
     * @return list<array{product_id: string, name: string, medium: string|null, location: string|null, price_from: float|null}>
     */
    private function candidates(array $scenarios): array
    {
        $candidates = [];

        foreach ($scenarios as $scenario) {
            foreach (is_array($scenario['lines'] ?? null) ? $scenario['lines'] : [] as $line) {
                if (! is_array($line) || ! isset($line['product_id'])) {
                    continue;
                }

                $candidates[(string) $line['product_id']] = [
                    'product_id' => (string) $line['product_id'],
                    'name' => (string) ($line['name'] ?? ''),
                    'medium' => isset($line['medium']) ? (string) $line['medium'] : null,
                    'location' => isset($line['location']) ? (string) $line['location'] : null,
                    'price_from' => isset($line['unit_price']) ? (float) $line['unit_price'] : null,
                ];
            }
        }

        return array_values($candidates);
    }

    /**
     * @param  array<string, mixed>  $scenarios
     * @return list<array{key: string, name: string, estimated_subtotal: float|null}>
     */
    private function scenarioSummaries(array $scenarios): array
    {
        $summaries = [];

        foreach ($scenarios as $key => $scenario) {
            if (! is_array($scenario)) {
                continue;
            }

            $summaries[] = [
                'key' => (string) $key,
                'name' => (string) ($scenario['name'] ?? $key),
                'estimated_subtotal' => isset($scenario['estimated_subtotal']) ? (float) $scenario['estimated_subtotal'] : null,
            ];
        }

        return $summaries;
    }
}
