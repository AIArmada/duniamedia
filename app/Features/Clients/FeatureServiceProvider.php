<?php

declare(strict_types=1);

namespace App\Features\Clients;

use App\Features\Clients\Api\ListsCampaigns;
use App\Features\Clients\Api\ListsClientParties;
use App\Features\Clients\Api\ListsServices;
use App\Features\Clients\EntryPoints\Livewire\WorkExplorer;
use App\Features\Clients\Queries\ListClientParties;
use App\Features\Clients\Queries\ListServices;
use App\Features\Clients\Queries\SearchCampaigns;
use App\Platform\FeatureModule;
use Livewire\Livewire;

final class FeatureServiceProvider extends FeatureModule
{
    public function register(): void
    {
        Livewire::component('work-explorer', WorkExplorer::class);
        $this->app->bind(ListsCampaigns::class, SearchCampaigns::class);
        $this->app->bind(ListsClientParties::class, ListClientParties::class);
        $this->app->bind(ListsServices::class, ListServices::class);
    }
}
