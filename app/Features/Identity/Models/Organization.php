<?php

declare(strict_types=1);

namespace App\Features\Identity\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends BaseModel
{
    protected $table = 'organizations';

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
