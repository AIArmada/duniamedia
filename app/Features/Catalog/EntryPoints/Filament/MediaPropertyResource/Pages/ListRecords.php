<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Filament\MediaPropertyResource\Pages;

use App\Features\Catalog\EntryPoints\Filament\MediaPropertyResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = MediaPropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
