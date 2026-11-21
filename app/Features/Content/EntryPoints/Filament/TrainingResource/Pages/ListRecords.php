<?php

declare(strict_types=1);

namespace App\Features\Content\EntryPoints\Filament\TrainingResource\Pages;

use App\Features\Content\EntryPoints\Filament\TrainingResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = TrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
