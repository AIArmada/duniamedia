<?php

declare(strict_types=1);

namespace App\Features\Content\EntryPoints\Filament\ArticleResource\Pages;

use App\Features\Content\EntryPoints\Filament\ArticleResource;
use Filament\Actions\CreateAction;

class ListRecords extends \Filament\Resources\Pages\ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
