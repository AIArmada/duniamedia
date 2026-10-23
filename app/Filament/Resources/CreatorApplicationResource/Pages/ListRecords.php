<?php
namespace App\Filament\Resources\CreatorApplicationResource\Pages;
use App\Filament\Resources\CreatorApplicationResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=CreatorApplicationResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
