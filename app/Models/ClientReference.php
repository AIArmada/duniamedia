<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientReference extends BaseModel
{
    protected function casts(): array
    {
        return ['permission_granted' => 'boolean', 'sort_order' => 'integer'];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'client_party_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
