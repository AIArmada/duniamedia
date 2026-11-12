<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdFormat extends BaseModel
{
    protected function casts(): array
    {
        return ['specification' => 'array', 'default_duration_seconds' => 'integer'];
    }

    public function placements(): BelongsToMany
    {
        return $this->belongsToMany(Placement::class, 'placement_formats', 'format_id', 'placement_id')
            ->withPivot('is_default');
    }

    public function creatorDeliveryTemplates(): HasMany
    {
        return $this->hasMany(CreatorDeliveryTemplate::class, 'format_id');
    }
}
