<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\EnquiryResource\Pages;

use App\Features\Sales\EntryPoints\Filament\EnquiryResource;
use App\Features\Sales\Persistence\WriteEnquiries;
use Illuminate\Database\Eloquent\Model;

class EditRecord extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = EnquiryResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(WriteEnquiries::class)->update($record, $data);
    }
}
