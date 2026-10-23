<?php
namespace App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=QuoteResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
