<?php

declare(strict_types=1);

namespace App\Features\Clients\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Brand extends BaseModel
{
    protected $table = 'brands';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
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
