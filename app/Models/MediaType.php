<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MediaType extends BaseModel
{
    public function group(): BelongsTo
    {
        return $this->belongsTo(MediaGroup::class, 'media_group_id');
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(MediaProperty::class, 'property_media_types', 'media_type_id', 'media_property_id');
    }
}
