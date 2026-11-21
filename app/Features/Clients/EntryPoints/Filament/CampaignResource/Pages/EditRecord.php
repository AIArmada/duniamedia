<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Filament\CampaignResource\Pages;

use App\Features\Clients\EntryPoints\Filament\CampaignResource;
use App\Features\Clients\Persistence\WriteCampaigns;
use Illuminate\Database\Eloquent\Model;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = CampaignResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(WriteCampaigns::class)->update($record, $data);
    }
}
