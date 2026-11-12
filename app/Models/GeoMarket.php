<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeoMarket extends BaseModel
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_market_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_market_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
