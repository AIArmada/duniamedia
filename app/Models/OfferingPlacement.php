<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferingPlacement extends BaseModel
{
    protected function casts(): array
    {
        return ['units_per_purchase' => 'decimal:3'];
    }

    public function offering(): BelongsTo
    {
        return $this->belongsTo(MediaOffering::class, 'offering_id');
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }
}
