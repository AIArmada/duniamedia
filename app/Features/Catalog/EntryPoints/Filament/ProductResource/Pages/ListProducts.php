<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Filament\ProductResource\Pages;

use App\Features\Catalog\EntryPoints\Filament\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
