<?php

use App\Platform\PlatformServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;

return [
    PlatformServiceProvider::class,
    AppServiceProvider::class,
    AdminPanelProvider::class,
];
