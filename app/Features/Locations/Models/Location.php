<?php

declare(strict_types=1);

namespace App\Features\Locations\Models;

use AIArmada\Addressing\Features\AddressLifecycle\Traits\HasAddresses;
use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends BaseModel
{
    protected $table = 'locations';

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

    public function permits(): HasMany
    {
        return $this->hasMany(SitePermit::class);
    }
}
