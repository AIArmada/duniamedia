<?php

namespace App\Filament\Resources\QuoteLineResource\Pages;

use App\Filament\Resources\QuoteLineResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = QuoteLineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
