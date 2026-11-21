<?php

declare(strict_types=1);

namespace App\Features\Content;

use App\Features\Content\Api\PublishesArticles;
use App\Features\Content\Queries\SearchArticles;
use App\Platform\FeatureModule;

final class FeatureServiceProvider extends FeatureModule
{
    public function register(): void
    {
        $this->app->bind(PublishesArticles::class, SearchArticles::class);
    }
}
