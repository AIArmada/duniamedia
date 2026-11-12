<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyPlatform extends BaseModel
{
    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(MediaProperty::class, 'media_property_id');
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
