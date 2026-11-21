<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\QuoteLineResource\Pages;

use App\Features\Sales\EntryPoints\Filament\QuoteLineResource;
use App\Features\Sales\Persistence\WriteQuoteLines;
use Illuminate\Database\Eloquent\Model;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = QuoteLineResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(WriteQuoteLines::class)->create($data);
    }
}
