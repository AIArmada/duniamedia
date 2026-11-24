<?php

declare(strict_types=1);

namespace App\Features\Catalog;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Actions\RegisterMediaAsset;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Catalog\Api\PresentsRateLines;
use App\Features\Catalog\Api\PricesMedia;
use App\Features\Catalog\Api\RegistersMediaAssets;
use App\Features\Catalog\Api\ResolvesMediaProfiles;
use App\Features\Catalog\Models\MediaProfile;
use App\Features\Catalog\Queries\PresentMedia;
use App\Features\Catalog\Queries\PresentRateLines;
use App\Features\Catalog\Queries\PriceMedia;
use App\Features\Catalog\Queries\ResolveMediaProfiles;
use App\Platform\FeatureModule;

final class FeatureServiceProvider extends FeatureModule
{
    public function register(): void
    {
        $this->app->bind(PresentsMedia::class, PresentMedia::class);
        $this->app->bind(PresentsRateLines::class, PresentRateLines::class);
        $this->app->bind(PricesMedia::class, PriceMedia::class);
        $this->app->bind(RegistersMediaAssets::class, RegisterMediaAsset::class);
        $this->app->bind(ResolvesMediaProfiles::class, ResolveMediaProfiles::class);
    }

    public function boot(): void
    {
        parent::boot();

        Product::resolveRelationUsing(
            'mediaProfile',
            fn (Product $product) => $product->hasOne(MediaProfile::class, 'product_id'),
        );
    }
}
