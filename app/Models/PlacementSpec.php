<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementSpec extends BaseModel
{
    protected function casts(): array
    {
        return ['is_required' => 'boolean'];
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }
}
