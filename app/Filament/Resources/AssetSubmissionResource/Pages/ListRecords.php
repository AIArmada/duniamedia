<?php
namespace App\Filament\Resources\AssetSubmissionResource\Pages;
use App\Filament\Resources\AssetSubmissionResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=AssetSubmissionResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
