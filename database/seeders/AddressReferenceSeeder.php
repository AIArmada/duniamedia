<?php

namespace Database\Seeders;

use AIArmada\Addressing\Actions\SeedCountryGeographiesAction;
use AIArmada\Addressing\Database\Seeders\AddressCountrySeeder;
use Illuminate\Database\Seeder;

class AddressReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AddressCountrySeeder::class);

        app(SeedCountryGeographiesAction::class)->execute('MY');
    }
}
