<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MediaType extends BaseModel
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(MediaCategory::class, 'category_id');
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(MediaProperty::class, 'property_media_types', 'media_type_id', 'media_property_id');
    }
}
