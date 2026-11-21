<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Filament\ProjectResource\Pages;

use App\Features\Clients\EntryPoints\Filament\ProjectResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
