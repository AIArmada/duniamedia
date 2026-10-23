<?php
namespace App\Filament\Resources\QuoteItemResource\Pages;
use App\Filament\Resources\QuoteItemResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=QuoteItemResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
