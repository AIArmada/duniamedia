<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdFormat extends BaseModel
{
    protected $table = 'ad_formats';

    protected function casts(): array
    {
        return ['specification' => 'array', 'default_duration_seconds' => 'integer'];
    }

    public function placements(): BelongsToMany
    {
        return $this->belongsToMany(Placement::class, 'placement_formats', 'format_id', 'placement_id')
            ->withPivot('is_default');
    }
}
