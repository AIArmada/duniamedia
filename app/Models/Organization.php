<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends BaseModel
{
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_organization_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_organization_id');
    }
}
