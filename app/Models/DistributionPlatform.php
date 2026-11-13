<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributionPlatform extends BaseModel
{
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'owner_party_id');
    }
}
