<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStudyMetric extends BaseModel
{
    protected function casts(): array
    {
        return ['is_verified' => 'boolean', 'metric_value' => 'decimal:4'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('evidence')->singleFile();
    }

    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }
}
