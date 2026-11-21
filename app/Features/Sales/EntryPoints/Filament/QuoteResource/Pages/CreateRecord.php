<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\QuoteResource\Pages;

use App\Features\Sales\EntryPoints\Filament\QuoteResource;
use App\Features\Sales\Persistence\WriteQuotes;
use Illuminate\Database\Eloquent\Model;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = QuoteResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(WriteQuotes::class)->create($data);
    }
}
