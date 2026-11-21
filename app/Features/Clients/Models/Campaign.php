<?php

declare(strict_types=1);

namespace App\Features\Clients\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends BaseModel
{
    protected $table = 'campaigns';

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'budget_amount' => 'decimal:2',
            'starts_on' => 'date',
            'ends_on' => 'date',
            'published_at' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('media');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'campaign_brands')->withPivot('is_primary');
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(PerformanceMetric::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function displayCover(): string
    {
        return $this->getFirstMediaUrl('cover') ?: 'assets/img/outdoor.webp';
    }
}
