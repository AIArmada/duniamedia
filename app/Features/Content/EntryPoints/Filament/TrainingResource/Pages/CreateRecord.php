<?php

declare(strict_types=1);

namespace App\Features\Content\EntryPoints\Filament\TrainingResource\Pages;

use App\Features\Content\Actions\SaveTraining;
use App\Features\Content\EntryPoints\Filament\TrainingResource;
use Illuminate\Database\Eloquent\Model;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = TrainingResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(SaveTraining::class)->handle($data);
    }
}
