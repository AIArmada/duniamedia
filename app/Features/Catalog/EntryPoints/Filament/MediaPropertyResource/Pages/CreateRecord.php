<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Filament\MediaPropertyResource\Pages;

use App\Features\Catalog\EntryPoints\Filament\MediaPropertyResource;
use App\Features\Catalog\Persistence\WriteMediaProperties;
use Illuminate\Database\Eloquent\Model;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = MediaPropertyResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(WriteMediaProperties::class)->create($data);
    }
}
