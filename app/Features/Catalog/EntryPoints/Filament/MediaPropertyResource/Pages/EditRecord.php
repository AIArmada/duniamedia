<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Filament\MediaPropertyResource\Pages;

use App\Features\Catalog\EntryPoints\Filament\MediaPropertyResource;
use App\Features\Catalog\Persistence\WriteMediaProperties;
use Illuminate\Database\Eloquent\Model;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = MediaPropertyResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(WriteMediaProperties::class)->update($record, $data);
    }
}
