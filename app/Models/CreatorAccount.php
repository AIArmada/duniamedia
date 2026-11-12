<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreatorAccount extends BaseModel
{
    protected function casts(): array
    {
        return [
            'followers' => 'integer',
            'average_views' => 'integer',
            'engagement_rate' => 'decimal:2',
            'metrics_as_of' => 'date',
        ];
    }

    public function creatorProfile(): BelongsTo
    {
        return $this->belongsTo(CreatorProfile::class);
    }

    public function mediaProperty(): BelongsTo
    {
        return $this->belongsTo(MediaProperty::class);
    }
}
