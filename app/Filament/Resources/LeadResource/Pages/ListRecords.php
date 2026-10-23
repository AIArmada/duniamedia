<?php
namespace App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=LeadResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
