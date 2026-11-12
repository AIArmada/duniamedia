<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RateCardLine extends BaseModel
{
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'minimum_quantity' => 'decimal:3',
        ];
    }

    public function rateCard(): BelongsTo
    {
        return $this->belongsTo(RateCard::class);
    }

    public function offering(): BelongsTo
    {
        return $this->belongsTo(MediaOffering::class);
    }
}
