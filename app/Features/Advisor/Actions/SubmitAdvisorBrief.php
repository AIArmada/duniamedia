<?php

declare(strict_types=1);

namespace App\Features\Advisor\Actions;

use App\Features\Advisor\Models\AdvisorSession;
use App\Features\Advisor\Persistence\WriteAdvisorSessions;
use App\Features\Identity\Api\ManagesParties;
use App\Features\Sales\Api\RecordsEnquiries;

final readonly class SubmitAdvisorBrief
{
    public function __construct(
        private ManagesParties $parties,
        private RecordsEnquiries $enquiries,
        private WriteAdvisorSessions $sessions,
    ) {}

    /**
     * @param  array{name: string, email: string, phone?: string|null, company?: string|null}  $contact
     */
    public function handle(AdvisorSession $session, array $contact, bool $consent): AdvisorSession
    {
        $party = $this->parties->createWithContact([
            'name' => (string) $contact['name'],
            'email' => $contact['email'],
            'phone' => $contact['phone'] ?? null,
            'company' => $contact['company'] ?? null,
        ]);

        $enquiryId = $this->enquiries->record($party->value, [
            'source' => 'media_advisor',
            'subject' => $this->subject($session),
            'brief' => $this->briefText($session),
            'consent_at' => $consent ? now()->toIso8601String() : null,
            'budget_amount' => (float) $session->budget_amount,
            'currency_code' => (string) $session->currency_code,
            'metadata' => $this->metadata($session),
        ]);

        return $this->sessions->update($session, [
            'enquiry_id' => $enquiryId,
            'requester_name' => (string) $contact['name'],
            'requester_email' => (string) $contact['email'],
            'requester_company' => $contact['company'] ?? null,
            'requester_phone' => $contact['phone'] ?? null,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);
    }

    private function subject(AdvisorSession $session): string
    {
        $brief = is_array($session->brief_summary) ? $session->brief_summary : [];

        return 'Media advisor review · '.(string) ($brief['objective_label'] ?? $session->objective);
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

        return implode('. ', $parts).'.';
    }

    /**
     * @return array<string, mixed>
     */
    private function metadata(AdvisorSession $session): array
    {
        $recommendations = is_array($session->recommendations) ? $session->recommendations : [];
        $scenarios = is_array($recommendations['scenarios'] ?? null) ? $recommendations['scenarios'] : [];
        $key = (string) ($session->selected_scenario_key ?? 'balanced');
        $scenario = is_array($scenarios[$key] ?? null) ? $scenarios[$key] : [];

        return [
            'advisor_reference' => (string) $session->share_token,
            'advisor_scenario' => $key,
            'scenario_name' => (string) ($scenario['name'] ?? ''),
            'estimated_subtotal' => isset($scenario['estimated_subtotal']) ? (float) $scenario['estimated_subtotal'] : null,
            'generation_mode' => (string) $session->generation_mode,
            'brief' => $session->brief_summary,
            'shortlist' => is_array($session->customized_product_ids) ? $session->customized_product_ids : [],
            'services' => is_array($session->customized_services) ? $session->customized_services : [],
        ];
    }
}
