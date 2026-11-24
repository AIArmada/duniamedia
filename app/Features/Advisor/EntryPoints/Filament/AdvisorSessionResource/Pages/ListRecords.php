<?php

declare(strict_types=1);

namespace App\Features\Advisor\EntryPoints\Filament\AdvisorSessionResource\Pages;

use App\Features\Advisor\EntryPoints\Filament\AdvisorSessionResource;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = AdvisorSessionResource::class;
}
