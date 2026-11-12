<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignReport extends BaseModel
{
    protected function casts(): array
    {
        return ['summary' => 'array', 'published_at' => 'datetime'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('report')->singleFile();
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
