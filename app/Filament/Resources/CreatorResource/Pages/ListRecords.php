<?php
namespace App\Filament\Resources\CreatorResource\Pages;
use App\Filament\Resources\CreatorResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=CreatorResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
