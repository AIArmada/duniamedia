<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Placement extends BaseModel
{
    protected $table = 'placements';

    protected static function booted(): void
    {
        static::saving(function (Placement $placement): void {
            $hasProperty = $placement->media_property_id !== null;
            $hasSurface = $placement->asset_surface_id !== null;

            if ($hasProperty === $hasSurface) {
                throw new \InvalidArgumentException('A placement must reference exactly one of a media property or an asset surface.');
            }
        });
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(MediaProperty::class, 'media_property_id');
    }

    public function formats(): BelongsToMany
    {
        return $this->belongsToMany(AdFormat::class, 'placement_formats', 'placement_id', 'format_id')
            ->withPivot('is_default');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(PlacementSpec::class);
    }

    public function mediaProfiles(): HasMany
    {
        return $this->hasMany(MediaProfile::class);
    }
}
