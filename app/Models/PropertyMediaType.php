<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyMediaType extends BaseModel
{
    public function property(): BelongsTo
    {
        return $this->belongsTo(MediaProperty::class, 'media_property_id');
    }

    public function mediaType(): BelongsTo
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }
}
