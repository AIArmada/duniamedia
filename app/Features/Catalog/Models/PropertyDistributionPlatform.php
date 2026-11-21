<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyDistributionPlatform extends BaseModel
{
    protected $table = 'property_distribution_platforms';

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(MediaProperty::class, 'media_property_id');
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(DistributionPlatform::class, 'distribution_platform_id');
    }
}
