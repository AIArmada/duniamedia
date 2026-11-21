<?php

declare(strict_types=1);

namespace App\Features\Sales\Models;

use App\Features\Identity\Api\ResolvesParties;
use App\Shared\Models\BaseModel;
use Database\Factories\EnquiryFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enquiry extends BaseModel
{
    protected $table = 'enquiries';

    protected function casts(): array
    {
        return [
            'budget_amount' => 'decimal:2',
            'consent_at' => 'datetime',
            'qualified_at' => 'datetime',
            'proposal_at' => 'datetime',
            'won_at' => 'datetime',
            'lost_at' => 'datetime',
            'closed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function contactName(): string
    {
        return $this->requester_party_id === null
            ? 'Unknown'
            : app(ResolvesParties::class)->displayName($this->requester_party_id) ?? 'Unknown';
    }

    protected static function newFactory(): EnquiryFactory
    {
        return EnquiryFactory::new();
    }
}
