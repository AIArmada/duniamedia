<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Filament\ServiceResource\Pages;

use App\Features\Clients\EntryPoints\Filament\ServiceResource;
use App\Features\Clients\Persistence\WriteServices;
use Illuminate\Database\Eloquent\Model;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(WriteServices::class)->create($data);
    }
}
