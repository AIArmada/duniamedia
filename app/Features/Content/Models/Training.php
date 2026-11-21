<?php

declare(strict_types=1);

namespace App\Features\Content\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class Training extends BaseModel
{
    protected $table = 'trainings';

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function displayImage(): string
    {
        return $this->getFirstMediaUrl('image') ?: 'assets/img/training.webp';
    }
}
