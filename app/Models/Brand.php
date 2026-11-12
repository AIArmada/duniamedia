<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends BaseModel
{
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function advertisedItems(): HasMany
    {
        return $this->hasMany(AdvertisedItem::class);
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_brands')->withPivot('is_primary');
    }

    public function displayLogo(): string
    {
        return $this->getFirstMediaUrl('logo');
    }
}
