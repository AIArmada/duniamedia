<?php
namespace App\Filament\Resources\MediaListingResource\Pages;
use App\Filament\Resources\MediaListingResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=MediaListingResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
