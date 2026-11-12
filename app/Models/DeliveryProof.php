<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryProof extends BaseModel
{
    protected function casts(): array
    {
        return ['recorded_at' => 'datetime'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('proof')->singleFile();
    }

    public function flightPlacement(): BelongsTo
    {
        return $this->belongsTo(FlightPlacement::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'verified_by_party_id');
    }
}
