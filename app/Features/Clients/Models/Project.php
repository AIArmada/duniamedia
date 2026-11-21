<?php

declare(strict_types=1);

namespace App\Features\Clients\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends BaseModel
{
    protected $table = 'projects';

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'project_services')->withPivot('notes');
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function parties(): HasMany
    {
        return $this->hasMany(ProjectParty::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function displayImage(): string
    {
        return $this->getFirstMediaUrl('image') ?: 'assets/img/outdoor.webp';
    }
}
