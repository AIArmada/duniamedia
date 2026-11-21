<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\QuoteResource\Pages;

use App\Features\Sales\EntryPoints\Filament\QuoteResource;
use App\Features\Sales\Persistence\WriteQuotes;
use Illuminate\Database\Eloquent\Model;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = QuoteResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(WriteQuotes::class)->update($record, $data);
    }
}
