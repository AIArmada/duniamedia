<?php

declare(strict_types=1);

namespace App\Features\Content\EntryPoints\Filament\ArticleResource\Pages;

use App\Features\Content\Actions\SaveArticle;
use App\Features\Content\EntryPoints\Filament\ArticleResource;
use Illuminate\Database\Eloquent\Model;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(SaveArticle::class)->handle($data);
    }
}
