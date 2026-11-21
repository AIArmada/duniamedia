<?php

namespace Tests\Concerns;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\MediaProfile;

trait CreatesMediaOffers
{
    /**
     * @param  array<string, mixed>  $product
     * @param  array<string, mixed>  $profile
     */
    protected function createMediaOffer(array $product = [], array $profile = []): Product
    {
        $product = Product::factory()->create(array_merge([
            'type' => 'service',
            'status' => 'active',
            'visibility' => 'catalog_search',
            'price' => 0,
            'currency' => 'MYR',
            'requires_shipping' => false,
            'tracks_inventory' => false,
        ], $product));

        MediaProfile::factory()->create(array_merge([
            'product_id' => $product->id,
            'medium' => 'Outdoor',
            'unit_code' => 'package',
            'pricing_mode' => 'on_request',
            'availability_status' => 'on_request',
        ], $profile));

        return $product;
    }
}
