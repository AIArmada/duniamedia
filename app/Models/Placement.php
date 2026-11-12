<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Placement extends BaseModel
{
    protected static function booted(): void
    {
        static::saving(function (Placement $placement): void {
            $hasProperty = $placement->media_property_id !== null;
            $hasSurface = $placement->asset_surface_id !== null;

            if ($hasProperty === $hasSurface) {
                throw new \InvalidArgumentException('A placement must reference exactly one of a media property or an asset surface.');
            }
        });
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(MediaProperty::class, 'media_property_id');
    }

    public function surface(): BelongsTo
    {
        return $this->belongsTo(AssetSurface::class, 'asset_surface_id');
    }

    public function formats(): BelongsToMany
    {
        return $this->belongsToMany(AdFormat::class, 'placement_formats', 'placement_id', 'format_id')
            ->withPivot('is_default');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(PlacementSpec::class);
    }

    public function offerings(): BelongsToMany
    {
        return $this->belongsToMany(MediaOffering::class, 'offering_placements', 'placement_id', 'offering_id')
            ->withPivot('units_per_purchase');
    }

    public function inventoryPools(): HasMany
    {
        return $this->hasMany(InventoryPool::class);
    }

    public function bookingLines(): HasMany
    {
        return $this->hasMany(BookingLine::class);
    }

    public function flightPlacements(): HasMany
    {
        return $this->hasMany(FlightPlacement::class);
    }
}
