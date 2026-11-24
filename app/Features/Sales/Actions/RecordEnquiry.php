<?php

declare(strict_types=1);

namespace App\Features\Sales\Actions;

use App\Features\Sales\Api\RecordsEnquiries;
use App\Features\Sales\Models\Enquiry;

final readonly class RecordEnquiry implements RecordsEnquiries
{
    /**
     * @param  array{source: string, subject: string, brief?: string|null, consent_at?: string|null, budget_amount?: float|null, currency_code?: string|null, metadata?: array<string, mixed>}  $details
     */
    public function record(string $partyId, array $details): string
    {
        $enquiry = Enquiry::query()->create([
            'requester_party_id' => $partyId,
            'source' => $details['source'],
            'subject' => $details['subject'],
            'brief' => $details['brief'] ?? null,
            'budget_amount' => $details['budget_amount'] ?? null,
            'currency_code' => $details['currency_code'] ?? null,
            'status' => 'new',
            'consent_at' => $details['consent_at'] ?? now(),
            'metadata' => $details['metadata'] ?? null,
        ]);

        return $enquiry->id;
    }
}
