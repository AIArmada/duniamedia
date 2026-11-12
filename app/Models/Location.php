<?php

namespace App\Models;

use AIArmada\Addressing\Traits\HasAddresses;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends BaseModel
{
    use HasAddresses;

    protected function casts(): array
    {
        return ['is_public' => 'boolean'];
    }

    public function geoMarket(): BelongsTo
    {
        return $this->belongsTo(GeoMarket::class);
    }

    public function zones(): HasMany
    {
        return $this->hasMany(LocationZone::class);
    }

    public function hostStructures(): HasMany
    {
        return $this->hasMany(HostStructure::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(MediaAsset::class);
    }

    public function permits(): HasMany
    {
        return $this->hasMany(SitePermit::class);
    }
}
