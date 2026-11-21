<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\QuoteResource\Pages;

use App\Features\Sales\EntryPoints\Filament\QuoteResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
