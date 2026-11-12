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
            RatecardSeeder::class,
            CreatorSeeder::class,
            ContentSeeder::class,
            ClientWorkSeeder::class,
        ]);
    }
}
