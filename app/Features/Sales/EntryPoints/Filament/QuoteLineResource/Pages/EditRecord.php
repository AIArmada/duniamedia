<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\QuoteLineResource\Pages;

use App\Features\Sales\EntryPoints\Filament\QuoteLineResource;
use App\Features\Sales\Persistence\WriteQuoteLines;
use Illuminate\Database\Eloquent\Model;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = QuoteLineResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(WriteQuoteLines::class)->update($record, $data);
    }
}
