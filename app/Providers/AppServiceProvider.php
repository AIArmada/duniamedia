<?php

namespace App\Providers;

use AIArmada\Orders\Events\OrderCanceled;
use AIArmada\Products\Models\Product;
use App\Listeners\ReleaseOrderCapacity;
use App\Models\Location;
use App\Models\MediaProfile;
use App\Models\Party;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
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

        Event::listen(OrderCanceled::class, ReleaseOrderCapacity::class);

        Product::resolveRelationUsing(
            'mediaProfile',
            fn (Product $product) => $product->hasOne(MediaProfile::class, 'product_id'),
        );
    }
}
