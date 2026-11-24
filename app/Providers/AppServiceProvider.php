<?php

declare(strict_types=1);

namespace App\Providers;

use App\Host\Ai\LaravelAiMediaStrategist;
use App\Host\Filesystem\WorkMediaStore;
use App\Shared\Contracts\AdvisesMediaStrategy;
use App\Shared\Contracts\StoresWorkMedia;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StoresWorkMedia::class, WorkMediaStore::class);
        $this->app->bind(AdvisesMediaStrategy::class, LaravelAiMediaStrategist::class);
    }

    public function boot(): void
    {
        persons_register_morph_map('person');
    }
}
