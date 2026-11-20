<?php

namespace App\Providers;

use AIArmada\Products\Models\Product;
use App\Models\Location;
use App\Models\MediaProfile;
use App\Models\Party;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Relation::morphMap([
            'party' => Party::class,
            'location' => Location::class,
        ]);

        persons_register_morph_map('person');

        Product::resolveRelationUsing(
            'mediaProfile',
            fn (Product $product) => $product->hasOne(MediaProfile::class, 'product_id'),
        );
    }
}
