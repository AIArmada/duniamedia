<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseStudy extends BaseModel
{
    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('portfolio');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(CaseStudyProject::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(CaseStudyCampaign::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(CaseStudyMetric::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->where('visibility', 'public');
    }
}
