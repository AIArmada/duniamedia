<?php

declare(strict_types=1);

namespace App\Features\Creators\EntryPoints\Filament\CreatorProfileResource\Pages;

use App\Features\Creators\EntryPoints\Filament\CreatorProfileResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = CreatorProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
