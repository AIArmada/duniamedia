<?php

declare(strict_types=1);

namespace App\Features\Creators;

use App\Features\Creators\Api\ListsCreators;
use App\Features\Creators\Api\ResolvesCreators;
use App\Features\Creators\Queries\FindCreator;
use App\Features\Creators\Queries\SearchCreators;
use App\Platform\FeatureModule;

final class FeatureServiceProvider extends FeatureModule
{
    public function register(): void
    {
        $this->app->bind(ListsCreators::class, SearchCreators::class);
        $this->app->bind(ResolvesCreators::class, FindCreator::class);
    }
}
