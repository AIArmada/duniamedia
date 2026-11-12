<?php

namespace App\Providers;

use App\Models\Location;
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
    }
}
