<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Filament\EnquiryResource\Pages;

use App\Features\Sales\EntryPoints\Filament\EnquiryResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = EnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
