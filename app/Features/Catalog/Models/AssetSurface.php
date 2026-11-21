<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetSurface extends BaseModel
{
    protected $table = 'asset_surfaces';

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class, 'asset_surface_id');
    }
}
