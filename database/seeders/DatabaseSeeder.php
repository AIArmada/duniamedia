<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AddressReferenceSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            TaxSeeder::class,
            SignalSeeder::class,
            ReferenceSeeder::class,
            ServiceSeeder::class,
            ProjectSeeder::class,
            CatalogueInfrastructureSeeder::class,
            CreatorSeeder::class,
            MediaCatalogueSeeder::class,
            CapacityPoolSeeder::class,
            ContentSeeder::class,
            ClientWorkSeeder::class,
        ]);
    }
}
