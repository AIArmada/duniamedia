<?php

namespace Database\Seeders;

use AIArmada\Tax\Models\TaxClass;
use AIArmada\Tax\Models\TaxRate;
use AIArmada\Tax\Models\TaxZone;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $zone = TaxZone::query()->updateOrCreate(
            ['code' => 'MY'],
            [
                'name' => 'Malaysia',
                'type' => 'country',
                'countries' => ['MY'],
                'is_default' => true,
                'is_active' => true,
            ],
        );

        TaxClass::query()->updateOrCreate(
            ['slug' => 'sst-8'],
            [
                'name' => 'SST 8%',
                'description' => 'Malaysian service tax on media and advertising services.',
                'is_default' => true,
                'is_active' => true,
            ],
        );

        TaxRate::query()->updateOrCreate(
            ['zone_id' => $zone->id, 'tax_class' => 'sst-8'],
            [
                'name' => 'Service Tax 8%',
                'rate' => 800,
                'is_compound' => false,
                'is_shipping' => false,
                'priority' => 0,
                'is_active' => true,
            ],
        );
    }
}
