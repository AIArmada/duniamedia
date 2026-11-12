<?php

namespace App\Filament\Resources\MediaPropertyResource\Pages;

use App\Filament\Resources\MediaPropertyResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = MediaPropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
