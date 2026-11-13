<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaProperty extends BaseModel
{
    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'owner_party_id');
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

    public function creatorAccounts(): HasMany
    {
        return $this->hasMany(CreatorAccount::class, 'media_property_id');
    }

    public function displayLogo(): string
    {
        return $this->getFirstMediaUrl('logo');
    }
}
