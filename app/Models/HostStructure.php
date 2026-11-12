<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HostStructure extends BaseModel
{
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(LocationZone::class, 'zone_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'owner_party_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(MediaAsset::class);
    }
}
