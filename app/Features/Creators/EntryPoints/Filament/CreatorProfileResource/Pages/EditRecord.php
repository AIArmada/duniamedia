<?php

declare(strict_types=1);

namespace App\Features\Creators\EntryPoints\Filament\CreatorProfileResource\Pages;

use App\Features\Creators\EntryPoints\Filament\CreatorProfileResource;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = CreatorProfileResource::class;
}
