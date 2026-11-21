<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\BookingResource\Pages;

use App\Features\Sales\EntryPoints\Filament\BookingResource;
use App\Features\Sales\Persistence\WriteBookings;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(WriteBookings::class)->update($record, $data);
    }
}
