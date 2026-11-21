<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Filament\ProjectResource\Pages;

use App\Features\Clients\EntryPoints\Filament\ProjectResource;
use App\Features\Clients\Persistence\WriteProjects;
use Illuminate\Database\Eloquent\Model;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(WriteProjects::class)->update($record, $data);
    }
}
