<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\QuoteLineResource\Pages;

use App\Features\Sales\EntryPoints\Filament\QuoteLineResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = QuoteLineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
