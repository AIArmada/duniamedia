<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementSpec extends BaseModel
{
    protected $table = 'placement_specs';

    protected function casts(): array
    {
        return ['is_required' => 'boolean'];
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }
}
