<?php

declare(strict_types=1);

namespace App\Features\Sales\Api;

interface RecordsEnquiries
{
    /**
     * @param  array{source: string, subject: string, brief?: string|null, consent_at?: string|null, budget_amount?: float|null, currency_code?: string|null, metadata?: array<string, mixed>}  $details
     */
    public function record(string $partyId, array $details): string;
}
