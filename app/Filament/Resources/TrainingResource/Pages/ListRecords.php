<?php
namespace App\Filament\Resources\TrainingResource\Pages;
use App\Filament\Resources\TrainingResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=TrainingResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
