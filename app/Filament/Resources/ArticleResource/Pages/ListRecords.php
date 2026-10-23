<?php
namespace App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource;
class ListRecords extends \Filament\Resources\Pages\ListRecords {
 protected static string $resource=ArticleResource::class;
  protected function getHeaderActions():array{return [\Filament\Actions\CreateAction::make()];}
}
