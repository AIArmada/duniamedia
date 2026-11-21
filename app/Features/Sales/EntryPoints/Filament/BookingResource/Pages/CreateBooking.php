<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\BookingResource\Pages;

use App\Features\Sales\EntryPoints\Filament\BookingResource;
use App\Features\Sales\Persistence\WriteBookings;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(WriteBookings::class)->create($data);
    }
}
