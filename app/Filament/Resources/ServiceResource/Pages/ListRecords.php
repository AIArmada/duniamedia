<?php
namespace App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=ServiceResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
