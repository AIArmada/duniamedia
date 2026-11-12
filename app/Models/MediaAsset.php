<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaAsset extends BaseModel
{
    protected function casts(): array
    {
        return ['installed_at' => 'datetime', 'specs' => 'array'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(MediaProperty::class, 'media_property_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function hostStructure(): BelongsTo
    {
        return $this->belongsTo(HostStructure::class);
    }

    public function surfaces(): HasMany
    {
        return $this->hasMany(AssetSurface::class, 'asset_id');
    }

    public function placements(): HasMany
    {
        return $this->hasManyThrough(Placement::class, AssetSurface::class, 'asset_id', 'asset_surface_id');
    }

    public function displayImage(): string
    {
        return $this->getFirstMediaUrl('image');
    }
}
