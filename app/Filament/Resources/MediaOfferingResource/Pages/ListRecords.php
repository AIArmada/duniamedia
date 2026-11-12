<?php

namespace App\Filament\Resources\MediaOfferingResource\Pages;

use App\Filament\Resources\MediaOfferingResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = MediaOfferingResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
