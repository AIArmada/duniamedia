<?php

declare(strict_types=1);

namespace App\Features\Clients\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends BaseModel
{
    protected $table = 'services';

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_services')->withPivot('notes');
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function displayImage(): string
    {
        return $this->getFirstMediaUrl('image') ?: 'assets/img/digital.webp';
    }
}
