<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaProperty extends BaseModel
{
    protected $table = 'media_properties';

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function mediaTypes(): BelongsToMany
    {
        return $this->belongsToMany(MediaType::class, 'property_media_types', 'media_property_id', 'media_type_id');
    }

    public function distributionPlatforms(): BelongsToMany
    {
        return $this->belongsToMany(DistributionPlatform::class, 'property_distribution_platforms', 'media_property_id', 'distribution_platform_id')
            ->withPivot(['handle', 'url', 'is_primary']);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class, 'media_property_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(MediaAsset::class, 'media_property_id');
    }

    public function displayLogo(): string
    {
        return $this->getFirstMediaUrl('logo');
    }
}
