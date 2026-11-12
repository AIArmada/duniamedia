<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enquiry extends BaseModel
{
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

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'requester_party_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function contactEmail(): ?string
    {
        return $this->requester?->primaryEmail();
    }

    public function contactName(): string
    {
        return $this->requester?->display_name ?? 'Unknown';
    }
}
