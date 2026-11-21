<?php

declare(strict_types=1);

namespace App\Features\Locations\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeoMarket extends BaseModel
{
    protected $table = 'geo_markets';

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
