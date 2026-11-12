<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetSurface extends BaseModel
{
    public function asset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'asset_id');
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class, 'asset_surface_id');
    }
}
