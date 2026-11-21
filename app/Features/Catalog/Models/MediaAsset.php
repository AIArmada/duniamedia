<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaAsset extends BaseModel
{
    protected $table = 'media_assets';

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
