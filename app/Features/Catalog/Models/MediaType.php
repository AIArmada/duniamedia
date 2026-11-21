<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MediaType extends BaseModel
{
    protected $table = 'media_types';

    public function group(): BelongsTo
    {
        return $this->belongsTo(MediaGroup::class, 'media_group_id');
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(MediaProperty::class, 'property_media_types', 'media_type_id', 'media_property_id');
    }
}
