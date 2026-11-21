<?php

declare(strict_types=1);

namespace App\Features\Locations;

use App\Features\Locations\Actions\RegisterSite;
use App\Features\Locations\Api\RegistersSites;
use App\Features\Locations\Models\Location;
use App\Platform\FeatureModule;
use Illuminate\Database\Eloquent\Relations\Relation;

final class FeatureServiceProvider extends FeatureModule
{
    public function register(): void
    {
        $this->app->bind(RegistersSites::class, RegisterSite::class);
    }

    public function boot(): void
    {
        parent::boot();

        Relation::morphMap([
            'location' => Location::class,
        ]);
    }
}
