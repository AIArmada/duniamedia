<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\EnquiryResource\Pages;

use App\Features\Sales\EntryPoints\Filament\EnquiryResource;
use App\Features\Sales\Persistence\WriteEnquiries;
use Illuminate\Database\Eloquent\Model;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = EnquiryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(WriteEnquiries::class)->create($data);
    }
}
