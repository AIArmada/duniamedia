<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Filament\CampaignResource\Pages;

use App\Features\Clients\EntryPoints\Filament\CampaignResource;
use App\Features\Clients\Persistence\WriteCampaigns;
use Illuminate\Database\Eloquent\Model;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = CampaignResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(WriteCampaigns::class)->create($data);
    }
}
