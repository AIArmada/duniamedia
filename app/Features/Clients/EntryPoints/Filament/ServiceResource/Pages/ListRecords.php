<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Filament\ServiceResource\Pages;

use App\Features\Clients\EntryPoints\Filament\ServiceResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
