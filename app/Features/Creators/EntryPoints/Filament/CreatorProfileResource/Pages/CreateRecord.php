<?php

declare(strict_types=1);

namespace App\Features\Creators\EntryPoints\Filament\CreatorProfileResource\Pages;

use App\Features\Creators\EntryPoints\Filament\CreatorProfileResource;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = CreatorProfileResource::class;
}
