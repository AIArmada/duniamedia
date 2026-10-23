<?php
namespace App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=ProjectResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
