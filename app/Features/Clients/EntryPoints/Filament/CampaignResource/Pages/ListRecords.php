<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Filament\CampaignResource\Pages;

use App\Features\Clients\EntryPoints\Filament\CampaignResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
