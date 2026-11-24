<?php

declare(strict_types=1);

namespace App\Features\Advisor;

use App\Features\Advisor\EntryPoints\Livewire\MediaAdvisor;
use App\Platform\FeatureModule;
use Livewire\Livewire;

final class FeatureServiceProvider extends FeatureModule
{
    public function register(): void
    {
        Livewire::component('media-advisor', MediaAdvisor::class);
    }
}
