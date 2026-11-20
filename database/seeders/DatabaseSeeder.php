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
            SignalSeeder::class,
            ReferenceSeeder::class,
            ServiceSeeder::class,
            ProjectSeeder::class,
            CatalogueInfrastructureSeeder::class,
            CreatorSeeder::class,
            MediaCatalogueSeeder::class,
            ContentSeeder::class,
            ClientWorkSeeder::class,
        ]);
    }
}
