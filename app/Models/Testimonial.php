<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends BaseModel
{
    protected function casts(): array
    {
        return ['approved_on' => 'date', 'rating' => 'integer'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'author_party_id');
    }
}
