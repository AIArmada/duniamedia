<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends BaseModel
{
    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'subtotal_amount' => 'decimal:2',
            'sent_at' => 'datetime',
            'accepted_at' => 'datetime',
            'declined_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'seller_party_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'buyer_party_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(QuoteLine::class);
    }

    public function isExpired(): bool
    {
        return $this->valid_until !== null && $this->valid_until->copy()->endOfDay()->isPast();
    }

    public function canShowPrices(): bool
    {
        return $this->price_visibility === 'public'
            && $this->lines->isNotEmpty()
            && $this->lines->every(fn (QuoteLine $line): bool => $line->unit_price !== null);
    }
}
