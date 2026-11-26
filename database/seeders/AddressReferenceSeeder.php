<?php

namespace Database\Seeders;

use AIArmada\Addressing\Database\Seeders\AddressCountrySeeder;
use AIArmada\Addressing\Features\Geography\Actions\SeedCountryGeographiesAction;
use Illuminate\Database\Seeder;

class AddressReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AddressCountrySeeder::class);

        app(SeedCountryGeographiesAction::class)->execute('MY');
    }
}
