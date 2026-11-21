<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Filament\ServiceResource\Pages;

use App\Features\Clients\EntryPoints\Filament\ServiceResource;
use App\Features\Clients\Persistence\WriteServices;
use Illuminate\Database\Eloquent\Model;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(WriteServices::class)->update($record, $data);
    }
}
