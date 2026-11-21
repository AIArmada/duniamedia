<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyMediaType extends BaseModel
{
    protected $table = 'property_media_types';

    public function property(): BelongsTo
    {
        return $this->belongsTo(MediaProperty::class, 'media_property_id');
    }

    public function mediaType(): BelongsTo
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }
}
