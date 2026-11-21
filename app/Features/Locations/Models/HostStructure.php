<?php

declare(strict_types=1);

namespace App\Features\Locations\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostStructure extends BaseModel
{
    protected $table = 'host_structures';

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(LocationZone::class, 'zone_id');
    }
}
