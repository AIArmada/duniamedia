<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Filament\ProjectResource\Pages;

use App\Features\Clients\EntryPoints\Filament\ProjectResource;
use App\Features\Clients\Persistence\WriteProjects;
use Illuminate\Database\Eloquent\Model;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(WriteProjects::class)->create($data);
    }
}
